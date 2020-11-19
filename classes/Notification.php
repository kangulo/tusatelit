<?php
declare(strict_types=1);

defined( 'ABSPATH' ) || exit;
/**
 * Class to Handle Show/Hide messages in the Notification Tray.
 */
class Notification
{
    public $table_name = 'sat_notifications';

    public function addMessage(array $message )    
    {
        global $wpdb;

        if (empty($message)) return false;
        // only save the notification if no possible duplicate is found.
        if (!$this->isDoublicate($message))
        {

            return $wpdb->insert( $this->table_name, 
                ['sender_id' => isset($message['sender_id']) ? $message['sender_id'] : '', 
                'recipient_id' => isset($message['recipient_id']) ? $message['recipient_id'] : '',
                'type' => isset($message['type']) ? $message['type'] : '',
                'title' => isset($message['title']) ? $message['title'] : '',
                'message' => isset($message['message']) ? $message['message'] : '',
                'link' => isset($message['link']) ? $message['link'] : '',
                'reference' => isset($message['reference']) ? $message['reference'] : '',
                'created_at' => date('Y-m-d H:i:s')] 
            );         
        }
        return false;        
    }

    public function isDoublicate($message)
    {
        global $wpdb;

        if ( $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $this->table_name WHERE sender_id = %d AND recipient_id = %d AND reference = '%s'",
                $message['sender_id'],
                $message['recipient_id'],
                $message['reference']                
            )
        ) ) {
            return true;
        }
        return false;
    }

    public function getMessageFormated($user_id = NULL)
    {
        global $wpdb;
        $html = "";
        $ids = array();
        $total = 0;
        
        if ( ! $messages = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $this->table_name WHERE recipient_id = %d and unread=1",
                $user_id               
            )
        ) ) {                        
            $html = "You don't have any notifications";
            $response['messages'] = $html;
            $response['total']   = $total;
            return $response;
        }
        foreach ($messages as $msg) 
        {
            $total++;
            $ids[] = $msg->id;
            $html .='<li>
                <span class="close clear-notification" data-id="'. $msg->id. '"></span>
                <a href="'. $msg->link  . '">
                    <h6 class="title">' . $msg->title . '</h6>
                    <p>' . $msg->message . '</p>
                    <small class="text-muted">' . Utils::time2str($msg->created_at) . '</small>
                </a>
            </li>';   
        }
        $html .= '<a href="#" class="btn clear-all-notifications" data-id="[' . implode( ',', $ids )  . ']">Clear All  Notifications</a>';
        $response['messages'] = $html;
        $response['total']   = $total;
        return $response;
    }

    public function getMessages($user_id = NULL)
    {
        global $wpdb;

        if ( ! $messages = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $this->table_name WHERE recipient_id = %d and unread=1",
                $user_id               
            )
        ) ) {            
            return false;
        }
        return $messages;
    }

    public function markAsRead($notifications = array() )
    {
        global $wpdb;

        $result = false;

        if ( is_array( $notifications ) ) {
            foreach ( $notifications as $notification ) {
                $result = $wpdb->update( 
                    $this->table_name, 
                    array( 
                        'unread' => 0
                    ), 
                    array( 'id' => $notification )
                );
            }
            $result;
        }
        else
        {
            $result = $wpdb->update( 
                $this->table_name, 
                array( 
                    'unread' => 0
                ), 
                array( 'id' => $notifications )
            );
        }  
        return $result;      
    }


    
}
