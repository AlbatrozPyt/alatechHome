<?php

namespace app\controllers;

use app\models\Brand;
use app\models\Graphiccard;
use app\models\Machine;
use app\models\Machinehasstoragedevice;
use app\models\Motherboard;
use app\models\Powersupply;
use app\models\Processor;
use app\models\Rammemory;
use app\models\Storagedevice;
use app\models\User;
use Codeception\Lib\Interfaces\ActiveRecord;
use Exception;
use PhpParser\Node\Expr\Cast\Object_;
use PHPUnit\Framework\Error\Error;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\rest\Controller;

use yii\web\Response;
use function PHPUnit\Framework\isNull;

class ApiController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'machines' => ['PUT', 'POST', 'DELETE', 'GET']
            ]
        ];



        $behaviors['authenticator'] =[
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }


    public function actionLogout()
    {

        $tokenAuth = \Yii::$app->request->headers->get("Authorization");
        $tokenAuth = str_replace('Bearer ', '', $tokenAuth);

        if (User::findIdentityByAccessToken($tokenAuth) != null)
        {
            $_SESSION['user'] = null;
            return ['message' => 'Logout com sucesso'];
        }
        \Yii::$app->response->statusCode = 403;
        $_SESSION['user'] = null;
        return [$tokenAuth];
    }


    public function actionMotherboards($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Motherboard::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionProcessors($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Processor::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionRam($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Rammemory::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionStorage($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Storagedevice::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionGraphic($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Graphiccard::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionPower($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Powersupply::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionMachines($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Machine::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionBrands($pageSize = 20, $page = 0)
    {
        $provider = new ActiveDataProvider([
            'query' => Brand::find(),
            'pagination' => [
                'pageSize' => $pageSize,
                'page' => $page,
            ]
        ]);

        return $provider->getModels();
    }

    public function actionSearch($category, $q, $pageSize = 20, $page = 0)
    {
        $category = "app\\models\\" . ucfirst($category);

        $obj = [
            Motherboard::class,
            Processor::class,
            Machine::class,
            Storagedevice::class,
            Graphiccard::class,
            Rammemory::class,
            Machine::class,
            Powersupply::class,
            Brand::class,
        ];

        foreach ($obj as $class) {
            if ($class === $category) {
                $provider = new ActiveDataProvider([
                    'query' => $category::find(),
                    'pagination' => [
                        'pageSize' => $pageSize,
                        'page' => $page
                    ]
                ]);

                $response = $category::find()->filterWhere(['like', 'name', $q . '%', false])->all();
                return $response;
            }
        }

        return null;
    }


    public function actionCreate()
    {
        $machine = new Machine();
        if ($machine->load(Yii::$app->request->post(), '')) {

            $imageBase64 = Yii::$app->request->post('imageBase64');

            $imageBase64 = str_replace('data:image/jpeg;base64,', '', $imageBase64);
            $bin = base64_decode($imageBase64);
            $img_file = "../web/images/$machine->imageUrl.png";
            file_put_contents($img_file, $bin);

            $machine->Verificar();
            if (Yii::$app->response->statusCode === 200)
            {
                $machine->save();
                return $machine;
            }
            return $machine->Verificar();
        }
        Yii::$app->response->statusCode === 422;
        return ['Post error' => 'Verifique os dados enviados.'];
    }

    public function actionDelete($id)
    {
        $machine = Machine::findOne(['id' => $id]);

        if ($machine !== null && $machine->delete()) {
            return Yii::$app->response->statusCode = 204;
        }

        Yii::$app->response->statusCode = 404;
        return ['message' => 'Modelo de máquina não encontrado'];
    }

    public function actionUpdate($id)
    {
        $machine = Machine::findOne(['id' => $id]);

        if ($machine !== null && $machine->load(Yii::$app->request->post(), '')) {

            $imageBase64 = Yii::$app->request->post('imageBase64');

            $imageBase64 = str_replace('data:image/jpeg;base64,', '', $imageBase64);
            $bin = base64_decode($imageBase64);
            $img_file = "../web/images/$machine->imageUrl.png";
            file_put_contents($img_file, $bin);

            $machine->Verificar();

            if (Yii::$app->response->statusCode === 200)
            {
                $machine->save();
                return $machine;
            }

            return $machine->Verificar();

        } else {
            Yii::$app->response->statusCode === 422;
            return ['Post error' => 'Verifique os dados enviados.'];
        }
    }

    public function actionImages($id)
    {

        $response = \Yii::$app->response;

        try {
            $response->format = yii\web\Response::FORMAT_RAW;
            $response->headers->add('content-type', 'image/png');
            $img_data = file_get_contents("images/$id.png");
            $response->data = $img_data;

            return $response;
        } catch (Exception $e) {
            Yii::$app->response->statusCode = 404;
            $response->format = yii\web\Response::FORMAT_JSON;
            $response->headers->add('content-type', 'application/json');
            return ['message' => 'Imagem não encontrada'];
        }
    }

    public function actionVerify()
    {
        $machine = new Machine();
        $machine->load(Yii::$app->request->post(), '');

        return $machine->Verificar();
    }
}
