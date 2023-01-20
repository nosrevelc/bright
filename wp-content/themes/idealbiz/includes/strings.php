<?php

/**
 * Get Countries and continent
 *
 * @since  1.0.0
 * @return array social networks
 */
function templateStrings(){
  $continent_list = array (
    'EU' => __('Europe','idealbiz'),
    'NA' => __('North America','idealbiz'),
    'SA' => __('South America','idealbiz'),
    'AF' => __('Africa','idealbiz'),
    'AS' => __('Asia','idealbiz'),
    'OC' => __('Oceania','idealbiz'),
    'AN' => __('Antarctica','idealbiz')
  );
  return $continent_list;
}


add_action('init', function() {
pll_register_string('Hello','Hello', 'irs',true);
pll_register_string('Message:','Message:', 'irs',true);
pll_register_string('[idealBiz] You have a new message in your service request','[idealBiz] You have a new message in your service request', 'irs',true);
pll_register_string('You have a new message related to your service request for Business Support Services.','You have a new message related to your service request for Business Support Services.','irs',true);
pll_register_string('To view this message and reply please go to your personal area using the following link %SERVICE_REQUEST_LINK%','To view this message and reply please go to your personal area using the following link %SERVICE_REQUEST_LINK%','irs');
pll_register_string('Thank you,','Thank you,','irs');
pll_register_string('View your service request link','View your service request link','irs');
pll_register_string('View your service request','View your service request','irs');
pll_register_string('irsAdjudication','SR Adjudication','irs');
pll_register_string('irsIntermediate','SR Intermediate','irs');
pll_register_string('irsConclusion','SR Conclusion','irs'); 
pll_register_string('completed.','completed.','irs');
pll_register_string('Payment for the','Payment for the','irs');
pll_register_string('Accept and payment link','Accept and payment link','irs');
pll_register_string('Accept and payment','Accept and payment','irs');
pll_register_string('Service satisfaction','Service satisfaction','irs');
pll_register_string('The Service Request you referred is completed. Redeem your winnings.','The Service Request you referred is completed. Redeem your winnings.','irs');
pll_register_string('Your Referral Percentage','Your Referral Percentage','irs');
pll_register_string('Your Earnings: ','Your Earnings: ','irs');
pll_register_string('The Service Request you referred is completed.','The Service Request you referred is completed.','irs');
pll_register_string('You received this email because your service request to','You received this email because your service request to','irs');
pll_register_string('was nominated for','was nominated for','irs');
pll_register_string('You can follow and contact your expert in "My Account" area.','You can follow and contact your expert in "My Account" area.','irs');
pll_register_string('You have been assigned a user account on our portal to access','You have been assigned a user account on our portal to access','irs');
pll_register_string('click','click','irs');
pll_register_string('Your New Account Details','Your New Account Details','irs');
pll_register_string('[idealBiz] You have a new account in our website','[idealBiz] You have a new account in our website','irs');
pll_register_string('on the link below, or through this link:','on the link below, or through this link:','irs');
pll_register_string('entering the user information assigned to it.','entering the user information assigned to it.','irs');
pll_register_string('Invoice details:','Invoice details:','irs');
pll_register_string('Name','Name','irs'); 
pll_register_string('Idealbiz, S.A','Idealbiz, S.A','irs');
pll_register_string('NIF','NIF','irs');
pll_register_string('515242438','515242438','irs');
pll_register_string('Address','Address','irs');
pll_register_string('Rua Casal do Cego, Cci Covinhas 2415-315 Leiria','Rua Casal do Cego, Cci Covinhas 2415-315 Leiria','irs');
pll_register_string('New business collaboration','New business collaboration', 'irs');
pll_register_string('You are being selected to help in this business:','You are being selected to help in this business:','irs',true);
pll_register_string('You can view and contact the advertiser here:','You can view and contact the advertiser here:','irs');
pll_register_string('Thank you,','Thank you,','irs');
pll_register_string('Hello','Hello','irs');
pll_register_string('You selected this expert to help in your business:','You selected this expert to help in your business:','irs',true);
pll_register_string('You can contact the expert:','You can contact the expert:','irs',true);
pll_register_string('Hi Customer Support, New user just registered on {{SITE_NAME}} Username {{USERNAME}} Email {{EMAIL}} View {{USER_ADMIN_URL}}','Hi Customer Support, New user just registered on {{SITE_NAME}} Username {{USERNAME}} Email {{EMAIL}} View {{USER_ADMIN_URL}}','ajax-login-and-registration-modal-popup-pro',true);
pll_register_string('Hi, Someone requested a new password for the user: {{USERNAME}} If you think this is an error please ignore this email. If you need to define a new password do it through this link: {{CHANGE_PASSWORD_URL}}','Hi, Someone requested a new password for the user: {{USERNAME}} If you think this is an error please ignore this email. If you need to define a new password do it through this link: {{CHANGE_PASSWORD_URL}}','ajax-login-and-registration-modal-popup-pro',true);
pll_register_string('Hi ###USERNAME###, This notice confirms that your password was changed on ###SITENAME###. If you did not change your password, please contact the Site Administrator at ###ADMIN_EMAIL### This email has been sent to ###EMAIL### Regards,  All at ###SITENAME###  ###SITEURL###', 'Hi ###USERNAME###, This notice confirms that your password was changed on ###SITENAME###. If you did not change your password, please contact the Site Administrator at ###ADMIN_EMAIL### This email has been sent to ###EMAIL### Regards,  All at ###SITENAME###  ###SITEURL###','ajax-login-and-registration-modal-popup-pro',true);
pll_register_string('Confirmation of application for Specialist','Confirmation of application for Specialist','idealbiz',true);
pll_register_string('Hi {{expert}}, We have received your Specialist application form. We appreciate your trust in our platform','Hi {{expert}}, We have received your Specialist application form. We appreciate your trust in our platform','idealbiz',true);
pll_register_string('New application for Specialist','New application for Specialist','idealbiz',true);
pll_register_string('Hello Support, New Specialist registration on our iDealBiz.pt platform. Username: {{expert}} E-mail: {{email}} You can access {{link}} to view the registered application. ','Hello Support, New Specialist registration on our iDealBiz.pt platform. Username: {{expert}} E-mail: {{email}} You can access {{link}} to view the registered application. ','idealbiz',true);
pll_register_string('You can view it at the following link:','You can view it at the following link:','idealbiz',true);
pll_register_string('Regards, The iDealBiz team','Regards, The iDealBiz team','idealbiz',true);
pll_register_string('_str Hi','_str Hi','idealbiz',true);
/* pll_register_string('_str Hi {{expert}},  The information regarding the lead you purchased is now available in your service request panel.','_str Hi {{expert}},  The information regarding the lead you purchased is now available in your service request panel.',true);
pll_register_string('_str To view your leads, access the dashboard','_str To view your leads, access the dashboard',true);
pll_register_string('_str Thank you.','_str Thank you.',true);
pll_register_string('_str Lead purchase confirmation for','_str Lead purchase confirmation for',true);
pll_register_string('_str My Leads','_str My Leads',true); */

$strings= array(
  'Insert the title of the listing',
  'Insert the description of the listing',
  'Insert the image of the listing',
  'Please choose the most relevant category for your business. Our team will review your business later and provide support if you think there is a more suitable one!',
  'Attention: iDealBiz.io is a bilingual website. When submitting your ad, if you are operating in the Portuguese language select to publish in the same language.',
  'Select your Expert',
  'Country / Region',
  'Service',
  'Billing address',
  'Regards, The iDealBiz team',
  'ad registration confirmation',
  'Password Registery Change',
  'iDealBiz | Announcement Publication',
  'Welcome to our professional platform.',
  'Service',
  'iDealBiz - A marketplace for businesses',
  'Subscription:',
  'None of our specialists have these characteristics. We will look for a Specialist to contact the user.',
  'New service request in your account',
  'Hi {{expert}}, you have a new service request for the competence you announced in our platform with the maximum budget amount of {{budget_max}}.',
  'You can choose to receive or decline this contact at: {{service_requests_page}}. Thank you.',
  'Please enter a value for reference.',
  'Please enter a value for minimum.',
  'Please enter a value for maximum.',
  'Awaiting Expert Response',
  'Awaiting Purchase',
  'Pay',
  'Reject',
  'Sign Up Now',
  'Checkout',
  'Thanks for your order. It’s on-hold until we confirm that payment has been received. In the meantime, here’s a reminder of what you ordered:',
  'Apply',
  'Apply and Pay Fee',
  'Lead purchase confirmation',
  'Hi {{expert}}, Here are the contacts of your Service Request.',
  'Customer:',
  'Email:',
  'Phone:',
  'Delivery Date:',
  'Reference Value:',
  'Budget Min:',
  'Budget Max:',
  'Message:',
  'Thank you.',
  'Customer:',
  'Hi {{customer}}, Here are details of your Service Request.',
  'Expert:',
  'Your Details:',
  'Expert email:',
  'Awaiting payment validation',
  'View Lead',
  'Payment Error',
  'Payment Completed',
  'Expert accepted',
  'Hi {{expert}}, You have one contact in your account.',
  'Buy',
  'Contact purchase confirmation for ',
  'Hi {{expert}}, here are the message of your contact: ',
  'One Service Request',
  'has been rejected and now it is assigned to Customer Care.',
  'New Service Request rejected!',
  'Customer Care',
  'You will earn: ',
  'points',
  'Expert Bonus for Referral',
  'Thank you',
  '[idealBiz] You have a new message in your service request',
  '[idealBiz] You have a new service request in your account',
  'My account Requests:',
  '%1$s service request',
  'My account Requests: %MY_SERVICE_REQUESTS%',
  '[idealBiz] New service request',
  'Details data',
  'Conclusion Date: ', 
  'We appreciate your trust in iDealBiz. Please be advised that your Service Request has been successfully submitted.',
  'Reference:',
  'The iDealBiz Team',
  'Hello',
  'It received a new Service Request for its Area of Expertise',
  'with the Reference Value',
  'To accept, decline or reference the Service Request go to your Orders Dashboard at:{{service_requests_page}}',
  'Money Simbol',
  'Member:',
  'Pay:',
  'Request to Another Member',
  'View',
  'points',
  'Sent to Another Expert',
  'Accept',
  'See',
  'The Specialist you selected for your Service Request is not currently available to carry out the same.A new Specialist with experience in the same area of competence was referred to follow up on the Request.',
  'We remember your Order details',
  '[idealBiz] New referral service request from:',
  'New referencing between members',
  'Received a Member Service Request Reference for its Area of Expertise',
  'Previous reference:',
  'New Reference:',
  'made by the Member',
  'received a new referral from the Member',
  'Earnings',
  'Referenced',
  'Refer another Member',
  'Type in your reason',
  'has been rejected by the Customer',
  'One Service Request',
  'has been rejected and now it is assigned to Customer Care.',
  'Hi! Customer Care',
  'Service Request Rejected',
  'It is necessary to verify that the user',
  'needs follow-up or if a new Service Request was created by it',
  'Please modify your Order or select another Professional, and resubmit the form',
  'If you have any questions, please contact us',
  'Contact Link Page',
  'Get in touch',
  'My Referrals',
  'My Service Requests',
  'Add new Referral',
  'You have one contact in your account',
  'of which he is appointed as manager',
  'Buy Lead',
  'New Contact to the Ad',
  'who published on our platform',
  'To pay the lead and receive the contact details, do it here',
  'Lead purchase amount',
  'Hi Member',
  'You selected this expert: {{expert}} to help in your business: {{list}}',
  'You are being selected to help in this business: {{list}}',
  'You are being selected to help in this business:',
  'Reset Filter',
  'in',
  //EMAIL DE NOVO USER LISTA DE STRINGS - wp-content/plugins/ajax-login-and-registration-modal-popup-pro/free/includes/class-settings.php
  'Registration',
  'Subject',
  '{{SITE_NAME}} Your username and password info',
  'The email Subject to user about successful registration.',
  'Body',
  'You just registered on',
  'Url to login:',
  'Username:',
  'Your password is:',
  'Lost password',
  '{{SITE_NAME}} Your new password',
  'The email Subject to user with new password.',
  'New user registration on your site:',
  'The email Subject to user about successful registration.',
  'New user just registered on',
  'View:',
  //EMAIL DE NOVO USER
  'str_Video',
  'No Service requests found.',
  'You can view it at the following link:',
  'Addresses',
  '_str Please be informed that the referral to another member has been successfully submitted.',
  '_str You have been referred by the member',
  '_str Email of the member who referred you',
  '_str New reference service request',
/*   '_str Hi {{expert}},  The information regarding the lead you purchased is now available in your service request panel.',
  '_str To view your leads, access the dashboard',
  '_str Thank you.',
  '_str Lead purchase confirmation for',
  '_str My Leads', */

  '[idealBiz] _str New referral service request from:',
  '_str Referral Between Members',

  //Emails de recomendação de negócios.
  '_str You Received Recomemded Business',
  '_str Customer',
  '_str A new business recommendation has been created',
  '_str Follow the link below to make the payment, and after confirmation of payment the contact will be available in the recommendation area in your dashboard',
  '_str A new recommendation has been created among members',
  '_str A new business recommendation has been created between members',
  '_str Data of who made the recommendation',
  '_str Data of those who received recommendation',
  '_str Recommendation data',
  '_str Name',
  '_str Phone',
  '_str Email',
  '_str Informatio',
  //Valores Recomendação de serviços
  '_str Forwarding and Recommendations',
  '_str Service Orders',
  '_str Comission',
  '_str Value Lead',
  '_str Comission',
  '_str My Account',
  '_str Back',
  '_str Buttons',
  '_str Service Resquest',
  //INDIVIDUALIZAÇÃO DE MODO DE PAY LEAD
  '_STR ORDER ID',
  '_str Waiting for Payment Confirmation',
  '_str sr_Value',
  '_STR SOURCE',
  '_str Completed Payment',
  '_str View of the Released Lead',
  '_str View Lead Pay Later',
  '_STR DATE SAW',
  '_STR SENT TO',
  '_str No Action Available',
  '_STR COMMENT OF',
  '_STR IN',
  '_str Indication of Completed Business',
  '_str Type in your Comment',
  '_str Confirm Lead',
  '_str Confirm Deal',
  '_str Send to Another Member',
  '_str Awaiting payment',
  '_str Pay',
  '_STR REJECT DATE',
  '_str Reject',
  //Traduzir
  'str Waiting For Member to Pay',
  '_str Thank you for using iDealBiz',
  '_str Service Proposal Request',
  '_str Reference Value',
  '_str To accept, decline or reference the Service Request go to your Orders {{service_requests_page}}',
  '_str Your profile',
  '_str View Request',
  '_str Your order has been successfully submitted',
  '_str Wait...',
  '_str Step 1',
  '_str Step 2',
  '_str service',
  '_str Opportunity',
  '_str Company',
  '_str Order By Name',
  '_str Opportunity Proposal'

);


foreach ($strings as $s){
  pll_register_string($s, $s, 'idealbiz', true);
}

});