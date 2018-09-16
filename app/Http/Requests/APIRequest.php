<?php

namespace App\Http\Requests;

class APIRequest extends BaseRequest
{
    /**
     * Check params with conditions
     *
     * @params  array $params           All params
     *          array $paramsRequire    Params require
     *          array $paramsAllow      Params can be allow
     *
     * @return  array $results[ code, message, data ]
     * @Author  ThanhNT
     * @Date    30/05/2016
     * */
    public function checkParams($params = [], $paramsAllow = [], $paramsRequire = [])
    {
        $data = [];
        foreach ($paramsRequire as $param) {
            if (isset($params[$param])) {
                if ($params[$param] === '' || $params[$param] === null) {
                    return $this->_response(104);
                } else {
                    // Ok, param exist
                    $data[$param] = $params[$param];
                }
            } else {
                return $this->_response(102);
            }
        }

        if (isset($paramsAllow['json']) && is_array($paramsAllow['json'])) {
            foreach ($paramsAllow['json'] as $param) {
                json_decode($param);
                if (json_last_error() != JSON_ERROR_NONE) {
                    return $this->_response(105);
                } else {
                    $data[$param] = $params[$param];
                }
            }
        } else {
            // Do nothing
        }

        if (isset($paramsAllow['string']) && is_array($paramsAllow['string'])) {
            // Don't check string ????
            foreach ($paramsAllow['string'] as $param) {
                if( isset($params[$param]) ) {
                    $data[$param] = $params[$param];
                }
            }
        } else {
            // Do nothing
        }

        if (isset($paramsAllow['numeric']) && is_array($paramsAllow['numeric'])) {
            foreach ($paramsAllow['numeric'] as $condition => $paramNumeric) {
                foreach ($paramNumeric as $param) {
                    if (isset($params[$param])) {
                        if ($params[$param] === '' || $params[$param] === null) {
                            return $this->_response(104);
                        } else {
                            if (!is_numeric($params[$param])) {
                                return $this->_response(104);
                            } else {
                                // Check if(!($params[$param] . $condition))
                                $check = false;
                                eval("\$check = (" . $params[$param] . $condition . ");");
                                if (!$check) {
                                    return $this->_response(104);
                                } else {
                                    // This params is correct => continue
                                    $data[$param] = $params[$param];
                                }
                            }
                        }
                    } else {
                        // Don't exist this params  => continue, $paramRequire has been checked on top
                    }
                }
            }
        } else {
            // Don't exist param type numeric => continue
        }

        if( isset($paramsAllow['enum']) && is_array($paramsAllow['enum']) ) {
            foreach ($paramsAllow['enum'] as $param => $values) {
                if( isset($params[$param]) ) {
                    if( in_array($params[$param], $values) ) {
                        $data[$param] = $params[$param];
                    } else {
                        return $this->_response(104);
                    }
                } else {
                    $data[$param] = $values[0];
                }
            }
        }

        if( isset($paramsAllow['datetime']) && is_array($paramsAllow['datetime']) ) {
            foreach ($paramsAllow['datetime'] as $param => $condition) {
                if (isset($params[$param])) {
                    $tmp = date_create_from_format($condition, $params[$param]);
                    if( $tmp ) {
                        $data[$param] = $params[$param];
                    } else {
                        return $this->_response(104);
                    }
                }
            }
        }

        return $this->_response(100, $data);
    }

    private function _response($code = 100, $data = [])
    {
        $response['code']    = $code;
        $response['message'] = config('api.messages.' . $code);
        $response['data']    = $data;

        return $response;
    }
}
