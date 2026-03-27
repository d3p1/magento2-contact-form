<?php
/**
 * @description Contact AJAX action
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\ContactForm\Controller\Contact;

use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Contact\Model\MailInterface;

class Send extends Action
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var MailInterface
     */
    protected $_mail;

    /**
     * Constructor
     *
     * @param JsonFactory   $resultJsonFactory
     * @param MailInterface $mail
     * @param Context       $context
     */
    public function __construct(
        JsonFactory   $resultJsonFactory,
        MailInterface $mail,
        Context       $context
    ) {
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_mail              = $mail;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return Json
     */
    public function execute()
    {
        /** @var Http $request */
        $request = $this->getRequest();

        if (!$request->isAjax()) {
            return null;
        }

        /** @var array $data */
        $data            = array();
        $data['message'] = '';

        try {
            $this->_sendEmail($this->_validatedContactParams());
            $data['message'] = __('Thank you very much for your contact! You will get a response as soon as possible.');
        }
        catch (LocalizedException $e) {
            $data['message'] = __($e->getMessage());
        }
        catch (Exception $e) {
            $data['message'] = __('An unexpected error has occurred. Please, try again in a few minutes.');
        }

        return $this->_resultJsonFactory->create()->setData($data);
    }

    /**
     * Send contact email
     *
     * @param array $post
     *
     * @return void
     */
    private function _sendEmail($post)
    {
        $this->_mail->send($post['email'], ['data' => new DataObject($post)]);
    }

    /**
     * Validate contact form params
     *
     * @return array
     * @throws Exception
     */
    private function _validatedContactParams()
    {
        /** @var Http $request */
        $request = $this->getRequest();

        if (trim($request->getParam('name')) === '') {
            throw new LocalizedException(__('Name is missing.'));
        }

        if (trim($request->getParam('comment')) === '') {
            throw new LocalizedException(__('Comment is missing.'));
        }

        if (false === \strpos($request->getParam('email'), '@')) {
            throw new LocalizedException(__('Invalid email address.'));
        }

        return $request->getParams();
    }
}
