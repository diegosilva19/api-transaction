<?php
namespace App\Library\Validator\Request;
use Illuminate\Http\Request;

class ValidatorRequest
{
    protected $request;
    protected $rules;
    protected $messages;
    protected $customAttributes;
    protected $errors=[];


    public function __construct(Request $request,  array $rules, array $messages = [], array $customAttributes= [])
    {
        $this->request= $request;
        $this->rules= $rules;
        $this->messages= $messages;
        $this->customAttributes= $customAttributes;
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Contracts\Validation\Factory
     */
    protected function getValidationFactory()
    {
        return app('validator');
    }

    public function validate() : bool
    {
        $validator = $this->getValidationFactory()->make($this->request->all(), $this->rules, $this->messages);
        $valid= true;
        if ($validator->fails())
        {
            foreach($validator->messages()->getMessages() as $field_name => $messages) {
                foreach($messages AS $message) {
                    $this->errors[$field_name] = $message;
                }
            }
            $valid= false;
        }

        return $valid;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function getErrorsJson()
    {
        return json_encode($this->errors);
    }

    public function getErrosPrintable(string $separator= "\n") : string
    {
        return implode($separator, $this->errors);
    }



}
