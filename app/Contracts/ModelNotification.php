<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 8/14/2018
 * Time: 6:00 PM
 */

namespace App\Contracts;

interface ModelNotification
{
    /**
     * Return the title of the model. For example
     *  John Doe and 30 others likes {Tour to Silliong part one}
     * @return mixed
     */
    public function getNotificationMessage();

    /**
     *  Return link that will be used in notification
     *  For example  return route('posts.index')
     * @return mixed
     */
    public function getNotificationLink();
}