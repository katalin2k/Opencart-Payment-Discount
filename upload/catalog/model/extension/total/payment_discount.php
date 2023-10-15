<?php

class ModelExtensionTotalPaymentDiscount extends Controller
{
    public function getTotal($total)
    {
        if ($this->config->get('total_payment_discount_status') && $this->cart->getSubTotal()) {
            $discount_payment_method = $this->config->get('total_payment_discount_payment_type');
            $discount = $this->config->get('total_payment_discount_percentage');
			$desc_text = sprintf($this->config->get('total_payment_discount_description'), $discount.'%');
            $sort_order = $this->config->get('total_payment_discount_sort_order');

            // Loop through available payment methods
            foreach ($this->session->data['payment_methods'] as &$payment_method) {
                // Check if the current payment method matches
                if ($discount_payment_method == $payment_method['code']) {
                    // Update payment method title with discount percentage
                    $payment_method['title'] .= ' (-' . $discount . '%)';

                    // If payment method is selected, add the discount to totals
                    if ($this->session->data['payment_method']['code'] == $discount_payment_method) {
                        $total['totals'][] = array(
                            'code' => 'payment_discount',
                            'title' => $desc_text,
                            'value' => '-' . (($discount / 100) * $total['total']),
                            'sort_order' => $sort_order,
                        );

                        $total['total'] -= (($discount / 100) * $total['total']);
                    }
                }
            }
        }
    }
}
