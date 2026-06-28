<?php
// File: models/DefaultFAQ.php
// Provides a set of default FAQ entries for coupons when no admin FAQs are defined.
// This class can be used across the admin controller to supply fallback data.

class DefaultFAQ {
    /**
     * Generate an array of default FAQs for a given coupon name.
     * The placeholder {{coupon_name}} in the question/answer strings will be replaced
     * with the actual coupon title.
     *
     * @param string $couponName The name/title of the coupon/store.
     * @return array An array of associative arrays with keys 'question' and 'answer'.
     */
    public static function generate(string $couponName): array {
        $placeholders = [
            '{{coupon_name}}' => $couponName,
        ];
        // Define 10 generic FAQs. Feel free to adjust the content later.
        $templates = [
            [
                'question' => 'Why should I visit Coupons Peak for {{coupon_name}} coupons?',
                'answer'   => 'Coupons Peak collects the top discounts from {{coupon_name}}, even at the last minute while updating continually to ensure consumer savings. Coupons, promo codes, gift cards and many more can also be found on the website.'
            ],
            [
                'question' => 'Where to find {{coupon_name}} promo codes?',
                'answer'   => 'Right on the website of {{coupon_name}} or join Coupons Peak for more options of {{coupon_name}} promo codes.'
            ],
            [
                'question' => 'Will all {{coupon_name}} discounts automatically be applied at checkout?',
                'answer'   => 'No. It depends on each {{coupon_name}} deal. Some require you to apply a code at discount field while some are applied automatically.'
            ],
            [
                'question' => 'Can you give me a guide for using {{coupon_name}} coupon codes?',
                'answer'   => 'Follow the guide below to score {{coupon_name}} coupon: - Copy the coupon code that fits your order. - Navigate to {{coupon_name}} and add your favorite items into the cart. - Apply code at checkout and enjoy saving.'
            ],
            [
                'question' => 'Are there any {{coupon_name}} Gift Cards available?',
                'answer'   => 'If a {{coupon_name}} Gift Card is available, it will be aggregated above. Let’s check!'
            ],
            [
                'question' => 'How to use {{coupon_name}} coupon code?',
                'answer'   => 'Visit {{coupon_name}} on Coupons Peak and pick the coupon making your order the biggest saving. Click GET CODE or GET DEAL for code displaying and enjoy the discount.'
            ],
            [
                'question' => 'How often does {{coupon_name}} release a new coupon?',
                'answer'   => 'For normal days, there is no specific frequency for {{coupon_name}} coupon releasing, but it tends to give out once per month. On the peak times of shopping, deals and discounts will be constantly launched and much bigger.'
            ],
            [
                'question' => 'How to know if an item of {{coupon_name}} is eligible for a coupon?',
                'answer'   => 'Each option of {{coupon_name}} coupons or deals comes with a detailed description for eligible items and the discount rate. Pick the right choice for your order.'
            ],
            [
                'question' => 'How long are {{coupon_name}} deals valid?',
                'answer'   => '{{coupon_name}} will announce how long their promotional program will last. {{coupon_name}} deals will also be valid within that period of time.'
            ],
            [
                'question' => 'How are errors about {{coupon_name}} coupons reported?',
                'answer'   => "Let us know at 'Contact Us'. Describe your problems and the errors about {{coupon_name}} coupons in detail, we will solve it as soon as possible."
            ]
        ];

        $faqs = [];
        foreach ($templates as $tpl) {
            $question = strtr($tpl['question'], $placeholders);
            $answer   = strtr($tpl['answer'], $placeholders);
            $faqs[] = [
                'question' => $question,
                'answer'   => $answer,
            ];
        }
        return $faqs;
    }
}
?>
