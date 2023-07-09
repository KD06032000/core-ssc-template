<?php

namespace APS_Momo\Models;

class Parameter
{
     const PARTNER_CODE = "partnerCode";
     const ACCESS_KEY = "accessKey";
     const REQUEST_ID = "requestId";
     const AMOUNT = "amount";
     const ORDER_ID = "orderId";
     const ORDER_INFO = "orderInfo";
     const RETURN_URL = "returnUrl";
     const NOTIFY_URL = "notifyUrl";
     const REQUEST_TYPE = "requestType";
     const EXTRA_DATA = "extraData";
     const BANK_CODE = "bankCode";
     const TRANS_ID = "transId";
     const PAY_TRANS_ID = "transid";
     const MESSAGE = "message";
     const LOCAL_MESSAGE = "localMessage";
     const DESCRIPTION = "description";
     const PAY_URL = "payUrl";
     const DEEP_LINK = "deeplink";
     const QR_CODE = "qrCode";
     const ERROR_CODE = "errorCode";
     const STATUS = "status";
     const PAY_TYPE = "payType";
     const TRANS_TYPE = "transType";
     const ORDER_TYPE = "orderType";
     const MOMO_TRANS_ID = "momoTransId";
     const PAYMENT_CODE = "paymentCode";
     const DATE = "responseTime";
     const VERSION = "version";
     const HASH = "hash";
     const APP_PAY_TYPE = "appPayType";
     const APP_DATA = "appData";
     const SIGNATURE = "signature";
     const CUSTOMER_NUMBER = "customerNumber";
     const PARTNER_REF_ID = "partnerRefId";
     const PARTNER_TRANS_ID = "partnerTransId";
     const USERNAME = "userName";
     const PARTNER_NAME = "partnerName";
     const STORE_ID = "storeId";
     const STORE_NAME = "storeName";
    //URI for different processes in MOMO payment system:
     const PAY_GATE_URI = "/gw_payment/transactionProcessor";
     const PAY_APP_URI = "/pay/app";
     const PAY_POS_URI = "/pay/pos";
     const PAY_CONFIRMATION_URI = "/pay/confirm";
     const PAY_STATUS_URI = "/pay/query-status";
     const PAY_REFUND_URI = "/pay/refund";
     const PAY_QR_CODE_URI = "/pay/notify";
}