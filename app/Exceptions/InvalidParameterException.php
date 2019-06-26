<?php


namespace App\Exceptions;


use App\Model\Code;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class InvalidParameterException extends RuntimeException
{
    /**
     * The validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    public $validator;

    /**
     * The recommended response to send to the client.
     *
     * @var \Symfony\Component\HttpFoundation\Response|null
     */
    public $response;
    /**
     * The name of the error bag.
     *
     * @var string
     */
    public $errorBag;
    /**
     * @var int
     */
    public $status;

    /**
     * InvalidParameterException constructor.
     * @param \Illuminate\Contracts\Validation\Validator      $validator
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     * @param string                                          $errorBag
     * @param int                                             $code
     */
    public function __construct($validator, $code = Code::ERR_PARAMETER, $response = null, $errorBag = 'default')
    {
        parent::__construct('The given data was invalid.');
        $this->code      = $code;
        $this->response  = $response;
        $this->errorBag  = $errorBag;
        $this->validator = $validator;
    }

    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param array $messages
     * @return static
     */
    public static function withMessages(array $messages)
    {
        return new static(tap(ValidatorFacade::make([], []), static function (Validator $validator) use ($messages) {
            foreach ($messages as $key => $value) {
                foreach (Arr::wrap($value) as $message) {
                    $validator->errors()->add($key, $message);
                }
            }
        }));
    }

    /**
     * Set the HTTP status code to be used for the response.
     *
     * @param int $status
     * @return $this
     */
    public function status($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the error bag on the exception.
     *
     * @param string $errorBag
     * @return $this
     */
    public function errorBag($errorBag): self
    {
        $this->errorBag = $errorBag;

        return $this;
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    public function errors(): ?array
    {
        return $this->validator->errors()->messages();
    }

    /**
     * Get the underlying response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
