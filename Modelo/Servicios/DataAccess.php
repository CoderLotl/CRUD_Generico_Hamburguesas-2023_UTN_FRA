<?php

/**
 * [Description DataAccess]
 * An instance class with a static param that stores an associative array of keys and the paths to files.
 * Provides a widely generic approach to saving and reading JSON files containing structures that represent objects with their IDs,
 * and a very specific approach for managing burgers based on the premise of the PDF included with this class.
 */
class DataAccess
{
    /**
     * @var [type]
     */
    private static $paths;

    /**
     * Sets the path value for the given path-attribute.
     * @param string $name
     * @param string $value
     * 
     * @return [type]
     */
    public static function SetValues($paths)
    {
        self::$paths = $paths;
    }

    /**
     * Checks if the paths are set.
     * @return [type]
     * Returns true if all the paths are set, false if they are not.
     */
    private function CheckValues()
    {
        $allChecked = false;
        foreach(self::$paths as $path => $value)
        {
            if($value == null)
            {
                $allChecked = false;
                break;
            }
            else
            {
                $allChecked = true;
            }
        }
        
        return $allChecked;
    }

    /**
     * [ THIS FUNCTION WILL STOP THE FLOW OF THE PROGRAM ON ERROR ]
     * Returns the stored path based on the string; 'burgers', 'sales', or 'vouchers'.
     * @param string $path
     * 
     * @return [type]
     */
    private function ReturnPath(string $path)
    {
        $message = 'ERROR -> Paths not defined. Stopping.';
        if($this->CheckValues() == false)
        {            
            die($message);
        }
        else
        {
            if(array_key_exists($path, self::$paths))
            {
                if(file_exists(self::$paths[$path]))
                {
                    return self::$paths[$path];
                }
                else
                {
                    die("ERROR -> The file doesn't exist or there's an error in the path.");
                }
            }            
        }        
    }

    /**
     * Takes an object with ID. Checks the file if it exists and has anything inside.
     * If there's no file or nothing in the array, the starting ID is 1. Else it's defined by the last object's ID plus one.
     * Adds the object to the array.
     * Returns the array with the new object.
     * @param mixed $object
     * @param mixed $path
     * 
     * @return [type]
     */
    private function ProcessObject($object, $path)
    {
        $basePath = $path;
        $path = $this->ReturnPath($path);
        $id = 0;        
        $objects = [];
        if(file_exists($path))
        {               
            if(filesize($path) > 0)
            {
                $objects = $this->ReadObjectsFromFile($basePath);
                foreach($objects as $obj)
                {
                    $id = $obj->id;
                }
            }  
        }                
    
        $object->id = $id+1;
        array_push($objects, $object);
        return $objects;
    }

    /**
     * Takes an object of the BURGER class with ID. Checks the file if it exists and has anything inside.
     * If there's no file or nothing in the array, the starting ID is 1. Else it's defined by the last object's ID.
     * Adds the object to the array.
     * Returns the array with the new object.
     * @param mixed $burger
     * @param mixed $path
     * 
     * @return [type]
     */
    private function ProcessBurger($burger, $path)
    {        
        $basePath = $path;
        $path = $this->ReturnPath($path);
        $id = 0;            
        $objects = [];
        if(file_exists($path))
        {               
            if(filesize($path) > 0)
            {
                $objects = $this->ReadObjectsFromFile($basePath);
                foreach($objects as $anObject)
                {
                    $id = $anObject->id;
                }
            }  
        }                
        if(count($objects) > 0)
        {
            $found = false;
            foreach($objects as $anObject)
            {
                if($burger->dressing == $anObject->dressing && $burger->name == $anObject->name)
                {
                    $anObject->amount += $burger->amount;
                    $anObject->price = $burger->price;
                    $found = true;
                    break;
                }
            }
            if($found == false)
            {                
                $burger->id = $id + 1;
                array_push($objects, $burger);
            }
        }
        else
        {                
            $burger->id = $id + 1;
            array_push($objects, $burger);
        }
        return $objects;        
    }

    /**
     * @param mixed $array
     * @param string $type
     * 
     * @return [type]
     */
    public function SaveToFile($array, string $type)
    {
        return file_put_contents($this->ReturnPath($type), json_encode($array, JSON_PRETTY_PRINT));
    }

    /**
     * Saves objects to their corresponding JSON file.
     * @param mixed $object
     * @param string $path
     * @param bool $isBurguer
     * 
     * @return [type]
     */
    /**
     * @param mixed $object
     * @param string $path
     * @param bool $isBurguer
     * 
     * @return [type]
     */
    public function SaveObjectToFile($object, string $path, bool $isBurguer = false)
    {
        $objects = [];
        if($isBurguer == false)
        {
            $objects = $this->ProcessObject($object, $path);
        }
        else
        {
            $objects = $this->ProcessBurger($object, $path);            
        }
        return file_put_contents($this->ReturnPath($path), json_encode($objects, JSON_PRETTY_PRINT));
    }

    /**
     * Reads the corresponding file and returns an array of generic objects.
     * @param string $path
     * 
     * @return [type]
     */
    /**
     * @param string $path
     * 
     * @return [type]
     */
    private function ReadObjectsFromFile(string $path)
    {
        $path = $this->ReturnPath($path);
        if(file_exists($path))
        {            
            return json_decode(file_get_contents($path));
        }
        else
        {
            die('No such file.');
        }
    }

    /**
     * @param string $type
     * 
     * @return [type]
     */
    public static function ReadFunctionalObjectsFromFile(string $type)
    {
        $dataAccess = new DataAccess;
        $rawObjects = $dataAccess->ReadObjectsFromFile($type);

        switch($type)
        {
            case 'burgers':
                return $dataAccess->ReturnFunctionalBurgers($rawObjects);
            case 'vouchers':
                return $dataAccess->ReturnFunctionalVouchers($rawObjects);
            case 'sales':
                return $dataAccess->ReturnFunctionalSales($rawObjects);
        }
    }

    /**
     * @param mixed $array
     * 
     * @return [type]
     */
    private function ReturnFunctionalBurgers($array)
    {
        $burgers = [];
        foreach($array as $obj)
        {
            $burger = new Burger($obj->name, $obj->amount, $obj->price, $obj->type, $obj->dressing);
            $burger->id = $obj->id;
            array_push($burgers, $burger);
        }
        return $burgers;
    }

    /**
     * @param mixed $array
     * 
     * @return [type]
     */
    private function ReturnFunctionalVouchers($array)
    {
        $vouchers = [];
        foreach($array as $obj)
        {
            $voucher = new Voucher();
            $voucher->used = $obj->used;
            $voucher->id = $obj->id;
            $voucher->date = $obj->date;
            array_push($vouchers, $voucher);
        }
        return $vouchers;
    }

    /**
     * @param mixed $array
     * 
     * @return [type]
     */
    private function ReturnFunctionalSales($array)
    {
        $sales = [];
        foreach($array as $obj)
        {
            $sale = new Sale($obj->email, $obj->name, $obj->type, $obj->amount, $obj->dressing);
            $sale->id = $obj->id;
            $sale->date = $obj->date;
            array_push($sales, $sale);
        }
        return $sales;
    }

    /**
     * Eliminates a single object from an array based on its ID.
     * Returns the array without the object.
     * @param int $id
     * @param mixed $array
     * 
     * @return [type]
     */
    public function Unset(int $id, $array)
    {        
        foreach($array as $obj)
        {
            if($obj->id == $id)
            {
                $index = array_search($obj, $array);
                unset($array[$index]);                
                break;
            }
        }
        return $array;
    }

    /**
     * Modifies any object given the paths to the files are correctly set.
     * Receives an associative array with a key and value to modify, the object's ID, then
     * searches for the object and modifies the attribute's value based on the given array.
     * @param int $id the object's ID.
     * @param string $type the object's type. The type has to have a file path set.
     * @param mixed $params an asociative array key-value with the values to modify.
     * 
     * @return [type]
     */
    public function Modify(int $id, string $type, $params)
    {
        $found = false;
        $objects = $this->ReadFunctionalObjectsFromFile($type);

        foreach($objects as $object)
        {
            if($object->id == $id)
            {
                foreach($params as $param => $value)
                {
                    if(property_exists($object, $param))
                    {
                        $object->$param = $value;
                    }
                }
                $found = true;
                break;
            }
        }
        if($found == false)
        {
            echo "The object couldn't be found.";
            return false;
        }
        else
        {
            $this->SaveToFile($objects, $type);
        }
    }
}