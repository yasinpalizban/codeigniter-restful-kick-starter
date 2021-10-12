<?php


namespace Modules\Shared\Interfaces;

interface SmsInterface
{

    /**
     * Send Sms.
     *
     *
     * @param string $message
     * @param string $rec
     * @param int $sms
     * @return int
     */
    public function sendSms(string $message, string $rec, int $sms): int;

    /**
     * Send Sms.
     *
     *
     * @param string $mobileNumber
     * @param string $footer
     * @return int
     */
    public function sendActivationCode(string $mobileNumber, string $footer): int;

    /**
     * Send Sms.
     *
     *
     * @param string $mobileNumber
     * @param string $message
     * @return int
     */
    public function sendCustomSms(string $mobileNumber, string $message): int;

    /**
     * Send Sms.
     *
     *
     * @param string $mobileNumber
     * @param string $code
     * @return bool
     */
    public function isActivationCodeValid(string $mobileNumber, string $code): bool;
}


