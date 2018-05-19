<?php

class csrf{

public function get_token_id() {
        if(isset($_SESSION['token_id'])) { 
                return $_SESSION['token_id'];
        }else{
            
            $token_id = $this->random(10);
            $_SESSION['token_id'] = $token_id;
            return $token_id;
        }
}

public function get_token() {
        if(isset($_SESSION['token_value'])) {
                return $_SESSION['token_value']; 
        }else{
            
            $token = hash('sha256', $this->random(500));
            $_SESSION['token_value'] = $token;
            return $token;
        }
}

public function check_valid($method){
        if($method == 'post' || $method == 'get') {
                $post = $_POST;
                $get = $_GET;
                if(isset(${$method}[$this->get_token_id()]) && (${$method}[$this->get_token_id()] == $this->get_token())) {
                        return true;
                }else{
                        return false;   
                }
        } else {
                return false;   
        }
}

public function form_names($names, $regenerate,$idname) {
 
        $values = array();
        foreach ($names as $n) {
                if($regenerate == true) {
                        unset($_SESSION[$idname][$n]);
                }
                $s = isset($_SESSION[$idname][$n]) ? $_SESSION[$idname][$n] : $this->random(10);
                $_SESSION[$idname][$n] = $s;
                $values[$idname][$n] = $s;       
        }
    return $values;
}

private function random($len) {

    $byteLen = intval(($len / 2) + 1);
    $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $len);
 
    return $return;
}

}