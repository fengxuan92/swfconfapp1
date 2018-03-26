<?php

namespace app\models;
use Yii;
use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;

class AtlassianRestApi extends Component
{
    public $crowdUrl = "http://crowd.calix.local:8095/crowd/rest";
    public $bitbucketUrl = "http://nandoc-91.calix.local:7991/bitbucket/rest";
    private $_httpClient;
    private $appName = "phpweb";
    private $appPasswd = "phpweb#";
    private $bitName="admin";
    private $bitPasswd="admin";
    /**
     * @inheritdoc
     */
    public function getHttpClient($baseUrl)
    {
        if (!is_object($this->_httpClient)){
            $this->_httpClient = Yii::createObject([
                'class' => Client::className(),
                'baseUrl' => $baseUrl ? $baseUrl : $this->baseUrl,
                'requestConfig' => [
                    'method' => "post",
                    'format' => Client::FORMAT_JSON
                ],
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public function findByUsername($username)
    {
        $response = $this->getHttpClient($this->crowdUrl)
            ->get('usermanagement/1/user.json?username='.$username,null,
                ['Authorization' => "Basic ".base64_encode($this->appName . ":". $this->appPasswd),
            ])->send();
        if( $response->isOK){
            $oRet = json_decode($response->content);
            return $oRet;
        } else {
            return false;
        }
    }

    /**
     * Return all users from crowd server
     *
     * @return array|null
     */
    public function getAllUsers()
    {
        $response = $this->getHttpClient($this->crowdUrl)
        ->get('usermanagement/1/search.json?max-results=4000&start-index=0&entity-type=user&expand=user',null,
                ['Authorization' => "Basic ".base64_encode($this->appName . ":". $this->appPasswd),
                ])->send();
                if( $response->isOK){
                    $oRet = json_decode($response->content);
                    return ArrayHelper::index($oRet->users,'name');
                } else {
                    return false;
                }
    }
    public function getAllProjects()
    {
        $response = $this->getHttpClient($this->bitbucketUrl)
        ->get('api/1.0/projects?limit=4000',null,
                ['Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
                ])->send();
                if( $response->isOK){
                    $oRet = json_decode($response->content);
                    return ArrayHelper::index($oRet->values,'name');
                } else {
                    return false;
                }
    }
    public function getAllRepos($project)
    {
        $response = $this->getHttpClient($this->bitbucketUrl)
        ->get("api/1.0/projects/$project/repos?limit=4000",null,
                ['Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
                ])->send();
        if( $response->isOK ){
            $oRepos = json_decode($response->content);
            return ArrayHelper::index($oRepos->values, 'name');
        } else {
            return false;
        }
    }
    public function getAllBranches($project)
    {
        $response = $this->getHttpClient($this->bitbucketUrl)
        ->get("api/1.0/projects/$project/repos?limit=4000",null,
                ['Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
                ])->send();
                if( $response->isOK){
                    $oRepos = json_decode($response->content);
                } else {
                    return false;
                }
        $oRet=[];
        foreach ($oRepos->values as $oRepo){
            $response = $this->getHttpClient($this->bitbucketUrl)
            ->get("api/1.0/projects/$project/repos/{$oRepo->slug}/branches?limit=4000",null,
                    ['Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
                    ])->send();
                    if( $response->isOK){
                        $oBranches = json_decode($response->content);
                    } else {
                        continue;
                    }
            $oRet = array_merge( $oRet, ArrayHelper::index($oBranches->values, 'displayId') );
        }
        return $oRet;
    }

    public function getBranchPermission($projectKey,$branch)
    {
      $response = $this->getHttpClient($this->bitbucketUrl)
          ->get("branch-permissions/2.0/projects/{$projectKey}/restrictions?matcherType=BRANCH&matcherId={$branch}&limit=4000", null,
          [ 'Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
          ])->send();
          if( $response->isOK){
            $oRet = json_decode($response->content);
          } else {
            return false;
          }
          return $oRet;
    }

    public function grantBranchPermissionOpen($projectKey,$branch)
    {
      $response = $this->getHttpClient($this->bitbucketUrl)
      ->get("branch-permissions/2.0/projects/{$projectKey}/restrictions?matcherType=BRANCH&matcherId={$branch}&limit=4000", null,
      [ 'Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
      ])->send();
      if( $response->isOK){
        $oRet = json_decode($response->content);
        if(count($oRet->values)==0) return true;
        foreach ($oRet->values as $oPerm){
          if($oPerm->type==="read-only"){
            $permId=$oPerm->id;
          }
        }
      }
      if(!isset($permId)) return false;
      $response = $this->getHttpClient($this->bitbucketUrl)
      ->delete("branch-permissions/2.0/projects/{$projectKey}/restrictions/{$permId}", null,
      [
          'Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
      ])->send();
      if( $response->isOK){
        return true;
      } else {
        return false;
      }
    }

    /**
     * grant write/deliver permission to users
     * @param string $projectKey
     * @param string $branch, branch name
     * @param string $users, user list contacted by ';' sign, null or empty will open permission to all users, single ";" char will denie all users
     * @return boolean|mixed
     */
    public function grantBranchPermission($projectKey,$branch,$users)
    {
      if(!isset($users)||empty($users)){
        return $this->grantBranchPermissionOpen($projectKey,$branch);
      }
      $response = $this->getHttpClient($this->bitbucketUrl)->createRequest()
          ->setUrl("branch-permissions/2.0/projects/{$projectKey}/restrictions?limit=4000")
          ->addHeaders([
              'Authorization' => "Basic ".base64_encode($this->bitName . ":". $this->bitPasswd),
          ]);
      if($users===";"){
        $response->setData([
           'type'=>'read-only',
           'matcher'=>[
               'id'=>"".$branch,
               "type"=>["id"=>"BRANCH"/* ,"name"=>"BRANCH" */],
               "active"=>true,
           ],
        ]);
      } else {
          $response->setData([
              'type'=>'read-only',
              'matcher'=>[
                  'id'=>"".$branch,
                  "type"=>["id"=>"BRANCH"/* ,"name"=>"BRANCH" */],
                  "active"=>true,
              ],
              'users'=> preg_split("/[;,]/", $users),
          ]);
      }
      $response=$response->send();
      if( $response->isOK){
        $oRet = json_decode($response->content);
      } else {
        $oRet = json_decode($response->content);
        if( isset($oRet)){
          return $oRet;
        }
        return false;
      }
      return $oRet;
    }

    /**
     * Validates user and password from crowd server
     *
     * @param string $username loginid to validate
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function authenUser($username,$password)
    {
        $response = $this->getHttpClient($this->crowdUrl)->createRequest()
            ->addHeaders([
                    'Authorization' => "Basic ".base64_encode($this->appName . ":". $this->appPasswd),
            ])
            ->setUrl('usermanagement/1/authentication.json?username='.$username)
            ->setData([
                'value' => $password,
            ])->send();
        if( $response->isOK){
            $oRet = json_decode($response->content);
            return $oRet;
        } else {
            return false;
        }
    }
}
