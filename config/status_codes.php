<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    "order_status_codes" => [
        "OrderInitiated"    => "ORD_INITIATED",
        "PaymentInitiated"  => "PAYMENT_INITIATED",
        "PaymentCompleted"  => "ORD_PLACED",
        "OrderRejected"     => "ORD_REJECTED",
        "OrderAccepted"     => "ORD_ACCEPTED",
        "OrderShipped"      => "ORD_SHIPPED",
        "OrderCompleted"    => "ORD_COMPLETED",
        "SettlementInProgress"  => "SETTLEMENT_IN_PROGRESS",
        "SettlementCompleted"   => "SETTLEMENT_COMPLETED",
        "OrderDisputed"     => "ORD_DISPUTED",
        "RefundInitiated"   => "REFUND_INITIATED",
        "RefundInProcess"   => "REFUND_IN_PROCRESS",
        "Refunded"          => "REFUND_COMPLETED",
        "SettlementOnHold"  => "SETTLEMENT_ON_HOLD",
        "LinkPaymentCompleted"      => "LINK_PAYMENT_COMPLETED",
        "RejectedRefundInProgress"  => "ORD_REJECTED_REFUND_IN_PROGRESS",
        "RejectedRefundCompleted"   => "ORD_REJECTED_REFUND_COMPLETED",
        "FailedRefundInProgress"    => "ORD_FAILED_REFUND_IN_PROGRESS",
        "FailedRefundCompleted"     => "ORD_FAILED_REFUND_COMPLETED"
    ],
    "settlement_status_code"    => [
        "NewTransaction"    => "NEW",
        "PgSettled"         => "PG_SETTLED",
        "SettlementInProgress"  => "SETTLEMENT_IN_PROGRESS",
        "SentToBank"        => "SENT_TO_BANK",
        "SentToWallet"      => "SENT_TO_WALLET",
        "Settled"           => "SETTLED",
        "SettlementOnHold"  => "SETTLEMENT_ON_HOLD",
        "AwaitingCorrection"=> "SETTLEMENT_AWAITING_CORRECTION"
    ]
];
