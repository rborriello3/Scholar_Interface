<?php 

/*
This will listen for when ever a message is sent to a user. Then it will store the message. 
 */

Event::listen('notification', function($message, $type, $from = 1, $user = null, $student = null, $attachment = null, $subject = null)
{
	$mess = new Messages();
	$mess->messageID = bin2hex(openssl_random_pseudo_bytes('12'));
	$mess->messageType = $type;
	$mess->message = $message;
	$mess->sentTime = strtotime('now');

	// These below are used from our sensible defaults. The can be however null in the DB.
	$mess->from = $from;
	$mess->toUser = $user;
	$mess->toStudent = $student;
	$mess->subject = $subject;
	$mess->attachments = $attachment;
	$mess->save();

	return false; // This is added to stop propogation of this event to other events if they ever appear

});
