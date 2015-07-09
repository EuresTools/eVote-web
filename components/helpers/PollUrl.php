<?php
namespace app\components\helpers;

use Yii;

class PollUrl extends \yii\helpers\Url
{
    /* // test if url should also use toRoute
    public static function to($url = '', $scheme = false)
    {

        return static::toRoute($url, $scheme);
    }
    */
    /*
    public static function to($url = '', $scheme = false)
    {
        if (is_array($url)) {
            return static::toRoute($url, $scheme);
        }

        $url = Yii::getAlias($url);
        if ($url === '') {
            $url = Yii::$app->getRequest()->getUrl();
        }

        if (!$scheme) {
            return $url;
        }

        if (strncmp($url, '//', 2) === 0) {
            // e.g. //hostname/path/to/resource
            return is_string($scheme) ? "$scheme:$url" : $url;
        }

        if (($pos = strpos($url, ':')) == false || !ctype_alpha(substr($url, 0, $pos))) {
            // turn relative URL into absolute
            $url = Yii::$app->getUrlManager()->getHostInfo() . '/' . ltrim($url, '/');
        }

        if (is_string($scheme) && ($pos = strpos($url, ':')) !== false) {
            // replace the scheme with the specified one
            $url = $scheme . substr($url, $pos);
        }

        return $url;
    }
    */

    /*
    * same function as BaseUrl but also calls addDefaultRouteParameters to add the poll route
    */
    public static function toRoute($route, $scheme = false)
    {
        $route = (array) $route;
        $route[0] = static::normalizeRoute($route[0]);

        if (substr($route[0], 0, 5) === "poll/") {
            // if the link goes to the poll controller use 'id' instead of 'poll_id'
            self::addDefaultRouteParameters($route, $parameterName = 'id');
        } else {
            // added add default Route Parameters for the poll_id
            self::addDefaultRouteParameters($route);
        }

        if ($scheme) {
            return Yii::$app->getUrlManager()->createAbsoluteUrl($route, is_string($scheme) ? $scheme : null);
        } else {
            return Yii::$app->getUrlManager()->createUrl($route);
        }
    }

    /*
    * adds the default poll_id parameters to the route if not given
    * @param array $route the route by reference.
    */
    public static function addDefaultRouteParameters(&$route, $parameterName = 'poll_id')
    {

        if (!isset ($route[$parameterName]) && method_exists(Yii::$app->controller, 'getPollId')) {

            $route[$parameterName] = Yii::$app->controller->getPollId();
        }
    }
}
