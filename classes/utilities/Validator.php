<?php


class Validator
{
    /**
     * Container contains the data that will be validated
     *
     * @var array
     */
    private $data;

    /**
     * Errors container
     *
     * @var array
     */
    private $errors = [];

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Determine if the given input is not empty
     *
     * @param string $inputName
     * @param string $message
     * @return \System\Validation
     */
    public function required($inputName, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($inputName, $this->errors)) {
            return $this;
        }

        // get the value of the input
        $value = $this->data[$inputName];

        if (empty($value)) {
            # generate the error message only if it doesn't provided
            $message = $message ?: sprintf('%s is required', $inputName);
            $this->errors[$inputName] = $message;
        }
        return $this;
    }

    /**
     * Determine if the given input file is an image
     *
     * @param string $inputName
     * @param string $message
     * @return \System\Validation
     */
    public function image($inputName, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($inputName, $this->errors)) {
            return $this;
        }

        ### @TODO we may use the photo class functions ^‿^

        return $this;
    }

    /**
     * Determine if the given input is a valid email
     *
     * @param string $inputName
     * @param string $message
     * @return \System\Validation
     */
    public function email($inputName, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($inputName, $this->errors)) {
            return $this;
        }

        // get the value of the input
        $value = $this->data[$inputName];

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            # generate the error message only if it doesn't provided
            $message = $message ?: sprintf("%s is not valid email", $inputName);
            $this->errors[$inputName] = $message;
        }
        return $this;
    }

    /**
     * Determine if the given input should be at least the given $length
     *
     * @param string $inputName
     * @param int $length
     * @param string $message
     * @return \System\Validation
     */
    public function minLen($inputName, $length, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($inputName, $this->errors)) {
            return $this;
        }

        // get the value of the input
        $value = $this->data[$inputName];

        if (strlen($value) < $length) {
            # generate the error message only if it doesn't provided
            $message = $message ?: sprintf('%s should be at least %d', $inputName, $length);
            $this->errors[$inputName] = $message;
        }
        return $this;
    }

    /**
     * Determine if the given input should be at most the given $length
     *
     * @param string $inputName
     * @param int $length
     * @param string $message
     * @return \System\Validation
     */
    public function maxLen($inputName, $length, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($inputName, $this->errors)) {
            return $this;
        }

        // get the value of the input
        $value = $this->data[$inputName];

        if (strlen($value) > $length) {
            # generate the error message only if it doesn't provided
            $message = $message ?: sprintf('%s should be at most %d', $inputName, $length);
            $this->errors[$inputName] = $message;
        }
        return $this;
    }

    /**
     * Determine if the input matches the second input
     *
     * @param string $firstInput
     * @param string $secondInput
     * @param string $message
     * @return \System\Validation
     */
    public function match($firstInput, $secondInput, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($secondInput, $this->errors)) {
            return $this;
        }

        // get the value of the inputs
        $firstValue = $this->data[$firstInput];
        $secondValue = $this->data[$secondInput];

        if ($firstValue != $secondValue) {
            # generate the error message only if it doesn't provided
            $message = $message ?: sprintf("%s should match %s", $secondInput, $firstInput);
            $this->errors[$secondInput] = $message;
        }
        return $this;
    }

    /**
     * Determine if the given input is unique in the database
     *
     * @param string $inputName
     * @param array $databaseData [table, col] || [table, col, exceptionCol, exceptionColValue]
     * @param string $message
     * @return \System\Validation
     */
    public function unique($inputName, $databaseData, $message = null)
    {
        // check if the input have this error only if it hasn't any previous errors
        if (array_key_exists($inputName, $this->errors)) {
            return $this;
        }

        // get the value of the input
        $value = $this->data[$inputName];

        $dbInstance = DB::getInstance();
        if (count($databaseData) == 2) {    # check if the value exists in the table
            list($table, $column) = $databaseData;
            $sql = "$column FROM $table WHERE $column = :value";
            $result = $dbInstance->select($sql, [
                ':value' => $value
            ]);
        } else {
            list($table, $column, $exceptionColumn, $exceptionColumnValue) = $databaseData;
            $sql = "$column FROM $table WHERE $column = :value AND $exceptionColumn != :exceptionValue";
            $result = $dbInstance->select($sql, [
                ':value'            => $value,
                ':exceptionValue'   => $exceptionColumnValue
            ]);
        }

        if (!empty($result)) {
            # generate the error message only if it doesn't provided
            $message = $message ?: sprintf("%s already Exists", $inputName);
            $this->errors[$inputName] = $message;
        }
        return $this;
    }

    /**
     * Determine if all inputs are valid
     *
     * @return bool
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Determine if there are any invalid input
     *
     * @return bool
     */
    public function fails() {
        return !empty($this->errors);
    }

    /**
     * Get all errors
     *
     * @return array
     */
    public function getMessages() {
        return $this->errors;
    }
}

/*================================
            How To Use
**================================
let: $data = [
    'name'  => 'Ahmed Mouhamed',
    'email' => 'ahmed@yahoo.com'
];

$validator = new Validator($data);

$validator->required('name')
          ->minLen('name', 5, 'please, write your name correctly')
          ->required('email')
          ->email('email')
          ->unique('email', ['accounts', 'email', 'id', 2]);

if ($validator->passes()) {
    # do something
}

if ($validator->fails()) {
    $errorsArr = $validator->getMessages();
}
================================*/
