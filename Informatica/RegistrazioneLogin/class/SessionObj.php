<?php
    class Session {
        private $id;
        private $sessionId;
        private $username;
        private $loginTime;
        private $logoutTime;

        public function __construct($values) {
            $this->id = isset($values['id']) ? $values['id'] : null;;
            $this->sessionId = isset($values['id_sessione']) ? $values['id_sessione'] : null;;
            $this->username = isset($values['username']) ? $values['username'] : null;;
            $this->loginTime = isset($values['data_login']) ? $values['data_login'] : null;;
            $this->logoutTime = isset($values['data_logout']) ? $values['data_logout'] : null;;
        }

        // Getter e Setter

            // ID
            public function getId() {
                return $this->id;
            }

            // Session ID
            public function getSessionId() {
                return $this->sessionId;
            }

            // Username
            public function getUsername() {
                return $this->username;
            }

            // Login Time
            public function getDataLogin() {
                return $this->loginTime;
            }

            // Logout Time
            public function getDataLogout() {
                return $this->logoutTime;
            }
    }