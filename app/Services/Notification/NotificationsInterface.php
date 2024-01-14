<?php

namespace App\Services\Notification;

use App\Models\Artisan;
use App\Models\Buyer;
use App\Models\Seller;

interface NotificationsInterface
{
    public function accountVerificationNotification(Artisan|Buyer|Seller $userType);

    public function passwordResetNotification();

    // public function phoneNumberVerificationNotification();

    // public function orderPlacementNotification($order);

    // public function sellerOrderPlacementNotification($phone, $order);

    // public function deliveryStatusChangeNotification($phone, $status, $order_code);

    // public function paymentStatusChangeNotification($order);

    // public function assignDeliveryBoyNotification($order);

    // public function welcomeUserNotification($userType);

    // public function newUserRegistrationInHouseManagementNotification();

    // public function newProductInHouseManagementNotification($product);

    // public function productUpdateInHouseManagementNotification($product, $oldDetails, $changes);

    // public function orderInvoiceNotification($order, $isPaymentLink = false);

    // public function inHouseManagementInvoiceNotification($order, $new = false);

    // public function sellerOrderInvoiceNotification($order);

    // public function deliveryBoyOrderInvoiceNotification($order);

    // public function newAgentSignUpInterestNotification($agent);

    // public function agentRequestApprovedNotification($agent);

    // public function agentRequestDeclinedNotification($agent);

    // public function supportTicketInHouseManagementNotification($ticket);

    // public function supportTicketReplyToUserNotification($ticket, $reply);

    // public function supportTicketReplyInHouseManagementNotification($ticket, $reply);

    // public function createdPublicGroupUserNotification($group, $user, $product);

    // public function joinedPublicGroupUserNotification($group, $user, $product);

    // public function publicGroupCompletedGroupMembersNotification($group, $user, $product);

    // public function publicGroupExpiredGroupMembersNotification($group, $user, $product);

    // public function publicGroupJoinedGroupMembersNotification($group, $user, $product);

    // public function userAddedToPrivateTeamNotification($phone, $quantity, $product, $user, $address);

    // public function productPriceIsOutOfRangeManagementNotification($product, $boundary);
}
