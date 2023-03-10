<?php

class Timefication
{
    private $unix_time;

    public function __construct($datetime = 'now')
    {
        $this->unix_time = strtotime($datetime);
    }

    /**
     * Nethod for casting this object to string
     * 
     * @return string
     */
    public function __toString()
    {
        return date('Y-m-d', $this->unix_time);
    }

    /**
     * Method for initialize the object
     * 
     * @param string $datetime
     * @return self
     */
    public function init(string $datetime = 'now'): self
    {
        return new self($datetime);
    }

    /**
     * Method for adding time from the given time
     * 
     * @param string $added_time
     * @return self
     */
    public function add (string $added_time)
    {
        $digit = preg_replace('/[^0-9.]+/', '', $added_time);
        $identifier = trim(preg_replace('/[0-9.]+/', '', $added_time));

        switch ($identifier) {
            case 'second':
            case 'seconds':
                $multiplier = 1;
                break;
            case 'minute':
            case 'minutes':
                $multiplier = 60;
                break;
            case 'hour':
            case 'hours':
                $multiplier = 60 * 60;
                break;
            case 'day':
            case 'days':
                $multiplier = 60 * 60 * 24;
                break;
            case 'week':
            case 'weeks':
                $multiplier = 60 * 60 * 24 * 7;
                break;
            case 'month':
            case 'months':
                $multiplier = 60 * 60 * 24 * 30;
                break;
            case 'year':
            case 'years':
                $multiplier = 60 * 60 * 24 * 365;
                break;
            case 'century':
            case 'centuries':
                $multiplier = 60 * 60 * 24 * 365 * 100;
                break;
        }

        $this->result_unix = $this->unix_time + ($digit * $multiplier);
        return $this;
    }

    /**
     * Method for reducing time from given time
     * 
     * @param string $reduced_time
     * @return self
     */
    public function reduce (string $reduced_time): self
    {
        $digit = preg_replace('/[^0-9.]+/', '', $reduced_time);
        $identifier = trim(preg_replace('/[0-9.]+/', '', $reduced_time));

        switch ($identifier) {
            case 'second':
            case 'seconds':
                $multiplier = 1;
                break;
            case 'minute':
            case 'minutes':
                $multiplier = 60;
                break;
            case 'hour':
            case 'hours':
                $multiplier = 60 * 60;
                break;
            case 'day':
            case 'days':
                $multiplier = 60 * 60 * 24;
                break;
            case 'week':
            case 'weeks':
                $multiplier = 60 * 60 * 24 * 7;
                break;
            case 'month':
            case 'months':
                $multiplier = 60 * 60 * 24 * 30;
                break;
            case 'year':
            case 'years':
                $multiplier = 60 * 60 * 24 * (date('Y', $this->unix_time) % 4 == 0 ? 366 : 365);
                break;
            case 'century':
            case 'centuries':
                $multiplier = 60 * 60 * 24 * 365 * 100;
                break;
            default:
                $multiplier = 1;
                break;
        }

        $this->result_unix = $this->unix_time - ($digit * $multiplier);
        return $this;
    }

    /**
     * Method for returning result with desired format (international)
     * 
     * @param string $format
     * @return string
     */
    public function format ($format): string
    {
        return date($format, $this->result_unix ?? $this->unix_time);
    }

    /**
     * Method for returning result with desired local format
     * 
     * @param string $format
     * @return string
     */
    public function local_format (string $format = null)
    {
        $fmt = datefmt_create(
            'id_ID',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            'Asia/Jakarta',
            IntlDateFormatter::GREGORIAN,
            $format ?? 'dd MMMM yyyy'
        );

        return datefmt_format($fmt, $this->result_unix ?? $this->unix_time);
    }
}