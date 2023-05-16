<?php

class DiscountController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);
        $this->viewData['discountRepo'] = DiscountRepository::readAll();
    }

    public function index()
    {
        $this->render('DiscountIndex',$this->viewData);
    }

    public function create()
    {
        $formData = $this->request->getData();
        $factor = $formData['discountFactor'];

        $discount = new Discount(null,$factor,0);

        $checkInsert = DiscountRepository::create($discount);

        if($checkInsert === false)
        {
            $response = new Response("Discount is not created",403);
            $response->send();
            exit();
        }

        $response = new Response("Discount created succesfully.",200);
        $response->send();
    }

    public function delete()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];

        $checkDelete = DiscountRepository::delete($this->viewData['discountRepo'][$id]);
        
        if($checkDelete === false)
        {
            $response = new Response("Discount can't be deleted",403);
            $response->send();
            exit();
        }
        
        $response = new Response("Discount deleted succesfully.",200);
        $response->send();

    }

    public function edit()
    {
        $formData = $this->request->getData();
        $id = $formData['id'];
        $this->viewData['crDiscount'] = $this->viewData['discountRepo'][$id];

        $this->render('DiscountEdit',$this->viewData);
    }

    public function process()
    {
        $formData = $this->request->getData();

        var_dump($formData);

        $id = intval($formData['id']);
        $factor = floatval($formData['factor']);
        $used = isset($formData['used']) ? 1 : 0;

        var_dump($factor);

        $discount = DiscountRepository::readById($id);
        $discount->setFactor($factor);
        $discount->setUsed($used);
        $checkEdit = DiscountRepository::update($discount);
        
        if($checkEdit === false)
        {
            $response = new Response("Discount can't be edited",403);
            $response->send();
            exit();
        }

        $response = new Response("Discount edited succesfully.",200);
        $response->send();

    }
}