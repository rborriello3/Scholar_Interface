<?php

Class ResponseController extends BaseController
{
    public function showHome()
    {
        return View::make('Content.Management.Response.home');
    }

    public function doResponseProcess()
    {
        $response = new ApplicationResponse();
        $responses = $response->updateResponse(Input::only('thankYou', 'acceptance', 'convocation', 'hiddenThankYou', 'hiddenAcceptance', 'hiddenConvocation'));

        if ($responses == TRUE)
        {
            return Redirect::route('showResponseHome')->with('success', 'Responses and awards have been processed');
        }

        return Redirect::route('showResponseHome')->with('error', 'Error in updating responses.');
    }

    public function doAcceptAward($GUID)
    {
        $response = new ApplicationResponse('', '');
        $return = $response->makeUpdatesToResponses($GUID, 1);

        if($return)
        {
            return Redirect::route('showResponseHome')->with('success', 'Award has been accepted');
        }

        return Redirect::route('showResponseHome')->with('error', 'Award could not be accepted');
    }

    public function doRedoAward($GUID)
    {
        $response = new ApplicationResponse('', '');
        $return = $response->makeUpdatesToResponses($GUID, 0);

        if($return || $return == 1)
        {
            return Redirect::route('showResponseHome')->with('success', 'Award has been revoked');
        }

        return Redirect::route('showResponseHome')->with('error', 'Award could not be revoked');
    }


}
