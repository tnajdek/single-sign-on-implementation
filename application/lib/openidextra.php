<?php

require_once('openid.php');

class LightOpenIDExtra extends LightOpenID {
    public function getPostArguments($immediate = false) {
        
        if ($this->setup_url && !$immediate) return $this->setup_url;
        if (!$this->server) $this->discover($this->identity);

        if ($this->version == 2) {
            return $this->agetPostArguments_v2($immediate);
        }
        throw new Exception("getPostArguments for open id v1 is not implemented yet");
    }
    
    protected function agetPostArguments_v2($immediate)
    {
        $params = array(
            'openid.ns'          => 'http://specs.openid.net/auth/2.0',
            'openid.mode'        => $immediate ? 'checkid_immediate' : 'checkid_setup',
            'openid.return_to'   => $this->returnUrl,
            'openid.realm'       => $this->trustRoot,
        );
        if ($this->ax) {
            $params += $this->axParams();
        }
        if ($this->sreg) {
            $params += $this->sregParams();
        }
        if (!$this->ax && !$this->sreg) {
            # If OP doesn't advertise either SREG, nor AX, let's send them both
            # in worst case we don't get anything in return.
            $params += $this->axParams() + $this->sregParams();
        }

        if ($this->identifier_select) {
            $params['openid.identity'] = $params['openid.claimed_id']
                 = 'http://specs.openid.net/auth/2.0/identifier_select';
        } else {
            $params['openid.identity'] = $this->identity;
            $params['openid.claimed_id'] = $this->claimed_id;
        }

        return array('url'=>$this->server,'params'=>$params);
    }
}

?>