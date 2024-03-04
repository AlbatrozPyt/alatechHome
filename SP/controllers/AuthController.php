<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use http\Client;
use Lcobucci\JWT\Token;
use sizeg\jwt\JwtHttpBearerAuth;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use sizeg\jwt\Jwt;
use yii\web\Session;
use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionLogin()
    {

        if ($_SESSION['user'] === null)
        {
            $user = new User();

            if ($user->load(\Yii::$app->request->post(), ''))
            {
                if ($user = User::findOne(['username' => $user->username, 'password' => $user->password])) {
                    $user->accessToken = $user->generateToken();
                    $user->save();
                    \Yii::$app->session->set('user', $user);
                    return ['token' => $user->accessToken];
                } else {
                    \Yii::$app->response->statusCode = 422;
                    return ['message' => 'Credenciais inválidas'];
                }
            }
        }

        return ['message' => 'Usuário já autenticado'];
    }

    public function actionLogout()
    {

        $tokenAuth = \Yii::$app->request->headers->set("Authorization:");

        if (User::findIdentityByAccessToken($tokenAuth) != null)
        {
            $_SESSION['user'] = null;
            return ['message' => 'Logout com sucesso'];
        }
        \Yii::$app->response->statusCode = 403;
        $_SESSION['user'] = null;
       return ['message' => 'Token inválido', 'token' => $tokenAuth];
    }
}