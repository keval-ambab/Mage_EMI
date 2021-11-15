<?php
namespace Ambab\EmiCalculator\Api\Data;

interface AllemiInterface
{
    const ID 			    = 'id';
    const BANKNAME 			= 'bank_name';
    const INTERESTRATE		= 'interest_rate';
    const DURATION		    = 'duration';
    const STATUS 			= 'status';

    
	public function getId();

	public function getBankName();

    public function getInterestRate();

    public function getDuration();

    public function getStatus();
	
    public function setId($id);
	
	public function setBankName($bank_name);
	
    public function setInterestRate($interest_rate);

    public function setDuration($duration);
	
    public function setStatus($status);
	
 
}
