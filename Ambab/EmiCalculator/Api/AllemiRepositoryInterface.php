<?php
namespace Ambab\EmiCalculator\Api;

interface AllemiRepositoryInterface
{
	public function save(\Ambab\EmiCalculator\Api\Data\AllemiInterface $emi);

    public function getById($id);

    public function delete(\Ambab\EmiCalculator\Api\Data\AllemiInterface $emi);

    public function deleteById($id);
}
