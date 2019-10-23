<?php
/**
 * RecoveryController
 * @package admin-me-recovery
 * @version 0.0.1
 */

namespace AdminMeRecovery\Controller;

use LibMailer\Library\Mailer;
use LibForm\Library\Form;
use LibUser\Library\Fetcher;

use AdminMeRecovery\Model\UserRecovery as URecovery;

class RecoveryController extends \Admin\Controller
{
    public function indexAction(){
        if($this->user->isLogin())
            return $this->show404();

        $form = new Form('admin.me.recovery');

        $params = [
            '_meta' => [
                'title' => 'Recovery'
            ],
            'error' => false,
            'success' => false,
            'form'  => $form
        ];

        if(!($valid = $form->validate()) || !$form->csrfTest('noob'))
            return $this->resp('me/recovery/index', $params, 'blank');

        $user = Fetcher::getOne(['email' => $valid->email]);
        if(!$user){
            $params['error'] = 'No user found with that email';
            return $this->resp('me/recovery/index', $params, 'blank');
        }

        $token = sha1(implode('-', [
            uniqid(),
            time(),
            $user->id,
            uniqid()
        ]));

        URecovery::create([
            'user' => $user->id,
            'hash' => $token,
            'expires' => date('Y-m-d H:i:s', strtotime('+2 hours'))
        ]);

        $mail = [
            'to' => [
                [
                    'name'  => $user->fullname,
                    'email' => $user->email
                ]
            ],
            'subject' => $this->config->name . ': Reset your password',
            'view' => [
                'path' => 'me/recovery/email',
                'params' => [
                    'user' => $user,
                    'link' => $this->router->to('adminMeRecoveryReset', ['token'=>$token])
                ]
            ]
        ];

        Mailer::send($mail);

        $params['success'] = true;
        return $this->resp('me/recovery/index', $params, 'blank');
    }

    public function resetAction(){
        if($this->user->isLogin())
            return $this->show404();

        $token = $this->req->param->token;
        $request = URecovery::getOne(['hash'=>$token]);
        if(!$request)
            return $this->show404();

        $expires = strtotime($request->expires);
        if($expires < time()){
            URecovery::remove(['id'=>$request->id]);
            return $this->show404();
        }

        $form = new Form('admin.me.recovery.reset');

        $params = [
            '_meta' => [
                'title' => 'Reset Password'
            ],
            'success' => false,
            'form'  => $form
        ];

        if(!($valid = $form->validate()) || !$form->csrfTest('noob'))
            return $this->resp('me/recovery/reset', $params, 'blank');

        if($valid->{'new-password'} != $valid->{'retype-password'}){
            $form->addError('retype-password', '0.0', 'The password is different');
            return $this->resp('me/recovery/reset', $params);
        }
        
        $new_password = $this->user->hashPassword($valid->{'new-password'});

        $user_set = ['password'=>$new_password];
        Fetcher::set($user_set, ['id'=>$request->user]);

        $params['success'] = true;
        return $this->resp('me/recovery/reset', $params, 'blank');
    }
}