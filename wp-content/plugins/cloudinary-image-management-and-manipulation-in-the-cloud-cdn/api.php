<?php

class CloudinaryApiError extends Exception {
}

class CloudinaryApiNotFound extends CloudinaryApiError {
}

class CloudinaryApiNotAllowed extends CloudinaryApiError {
}

class CloudinaryApiAlreadyExists extends CloudinaryApiError {
}

class CloudinaryApiRateLimited extends CloudinaryApiError {
}

class CloudinaryApiBadRequest extends CloudinaryApiError {
}

class CloudinaryApiGeneralError extends CloudinaryApiError {
}

class CloudinaryApiAuthorizationRequired extends CloudinaryApiError {
}

class CloudinaryApiResponse extends ArrayObject {
    function __construct($response) {
        parent::__construct(CloudinaryApi::parse_json_response($response));
        $this->rate_limit_reset_at  = strtotime($response->headers["X-FeatureRateLimit-Reset"]);
        $this->rate_limit_allowed   = intval($response->headers["X-FeatureRateLimit-Limit"]);
        $this->rate_limit_remaining = intval($response->headers["X-FeatureRateLimit-Remaining"]);
    }
}

class CloudinaryApi {
    static $CLOUDINARY_API_ERROR_CLASSES
        = [
            400 => "CloudinaryApiBadRequest",
            401 => "CloudinaryApiAuthorizationRequired",
            403 => "CloudinaryApiNotAllowed",
            404 => "CloudinaryApiNotFound",
            409 => "CloudinaryApiAlreadyExists",
            420 => "CloudinaryApiRateLimited",
            500 => "CloudinaryApiGeneralError"
        ];

    function resource_types($options = []) {
        return $this->call_api("get", ["resources"], [], $options);
    }

    protected function call_api($method, $uri, $params, &$options) {
        $prefix     = Cloudinary::option_get($options, "upload_prefix",
                                             Cloudinary::config_get("upload_prefix", "https://api.cloudinary.com"));
        $cloud_name = Cloudinary::option_get($options, "cloud_name", Cloudinary::config_get("cloud_name"));
        if (!$cloud_name) throw new InvalidArgumentException("Must supply cloud_name");
        $api_key = Cloudinary::option_get($options, "api_key", Cloudinary::config_get("api_key"));
        if (!$api_key) throw new InvalidArgumentException("Must supply api_key");
        $api_secret = Cloudinary::option_get($options, "api_secret", Cloudinary::config_get("api_secret"));
        if (!$api_secret) throw new InvalidArgumentException("Must supply api_secret");
        $api_url = implode("/", array_merge([$prefix, "v1_1", $cloud_name], $uri));
        $api_url .= "?" . preg_replace("/%5B\d+%5D/", "%5B%5D", http_build_query($params));
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $api_key . ":" . $api_secret);
        curl_setopt($ch, CURLOPT_CAINFO, realpath(dirname(__FILE__)) . "/cacert.pem");
        $response = $this->execute($ch);
        curl_close($ch);
        if ($response->responseCode == 200) {
            return new CloudinaryApiResponse($response);
        } else {
            $exception_class = Cloudinary::option_get(self::$CLOUDINARY_API_ERROR_CLASSES, $response->responseCode);
            if (!$exception_class) throw new CloudinaryApiGeneralError("Server returned unexpected status code - {$response->responseCode} - {$response->body}");
            $json = $this->parse_json_response($response);
            throw new $exception_class($json["error"]["message"]);
        }
    }

    protected function execute($ch) {
        $string  = curl_exec($ch);
        $headers = [];
        $content = '';
        $str     = strtok($string, "\n");
        $h       = NULL;
        while ($str !== FALSE) {
            if ($h and trim($str) === '') {
                $h = FALSE;
                continue;
            }
            if ($h !== FALSE and FALSE !== strpos($str, ':')) {
                $h = TRUE;
                list($headername, $headervalue) = explode(':', trim($str), 2);
                $headervalue = ltrim($headervalue);
                if (isset($headers[ $headername ])) {
                    $headers[ $headername ] .= ',' . $headervalue;
                } else {
                    $headers[ $headername ] = $headervalue;
                }
            }
            if ($h === FALSE) {
                $content .= $str . "\n";
            }
            $str = strtok("\n");
        }
        $result               = new stdClass;
        $result->headers      = $headers;
        $result->body         = trim($content);
        $result->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $result;
    }

    static function parse_json_response($response) {
        $result = json_decode($response->body, TRUE);
        if ($result == NULL) {
            $error = json_last_error();
            throw new CloudinaryApiGeneralError("Error parsing server response ({$response->responseCode}) - {$response->body}. Got - {$error}");
        }

        return $result;
    }

    function resources($options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $type          = Cloudinary::option_get($options, "type");
        $uri           = ["resources", $resource_type];
        if ($type) array_push($uri, $type);

        return $this->call_api("get", $uri, $this->only($options, ["next_cursor", "max_results", "prefix"]), $options);
    }

    protected function only(&$hash, $keys) {
        $result = [];
        foreach ($keys as $key) {
            if (isset($hash[ $key ])) $result[ $key ] = $hash[ $key ];
        }

        return $result;
    }

    function resources_by_tag($tag, $options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $uri           = ["resources", $resource_type, "tags", $tag];

        return $this->call_api("get", $uri, $this->only($options, ["next_cursor", "max_results"]), $options);
    }

    function resource($public_id, $options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $type          = Cloudinary::option_get($options, "type", "upload");
        $uri           = ["resources", $resource_type, $type, $public_id];

        return $this->call_api("get", $uri, $this->only($options,
                                                        ["exif", "colors", "faces", "image_metadata", "derived", "max_results"]),
                               $options);
    }

    function delete_resources($public_ids, $options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $type          = Cloudinary::option_get($options, "type", "upload");
        $uri           = ["resources", $resource_type, $type];

        return $this->call_api("delete", $uri,
                               array_merge(["public_ids" => $public_ids], $this->only($options, ["keep_original"])),
                               $options);
    }

    function delete_resources_by_prefix($prefix, $options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $type          = Cloudinary::option_get($options, "type", "upload");
        $uri           = ["resources", $resource_type, $type];

        return $this->call_api("delete", $uri,
                               array_merge(["prefix" => $prefix], $this->only($options, ["keep_original"])), $options);
    }

    function delete_resources_by_tag($tag, $options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $uri           = ["resources", $resource_type, "tags", $tag];

        return $this->call_api("delete", $uri, $this->only($options, ["keep_original"]), $options);
    }

    function delete_derived_resources($derived_resource_ids, $options = []) {
        $uri = ["derived_resources"];

        return $this->call_api("delete", $uri, ["derived_resource_ids" => $derived_resource_ids], $options);
    }

    # updates - currently only supported update is the "allowed_for_strict" boolean flag
    function tags($options = []) {
        $resource_type = Cloudinary::option_get($options, "resource_type", "image");
        $uri           = ["tags", $resource_type];

        return $this->call_api("get", $uri, $this->only($options, ["next_cursor", "max_results", "prefix"]), $options);
    }

    function transformations($options = []) {
        return $this->call_api("get", ["transformations"], $this->only($options, ["next_cursor", "max_results"]),
                               $options);
    }

    function transformation($transformation, $options = []) {
        $uri = ["transformations", $this->transformation_string($transformation)];

        return $this->call_api("get", $uri, $this->only($options, ["max_results"]), $options);
    }

    # Based on http://snipplr.com/view/17242/
    protected function transformation_string($transformation) {
        return is_string($transformation) ? $transformation
            : Cloudinary::generate_transformation_string($transformation);
    }

    function delete_transformation($transformation, $options = []) {
        $uri = ["transformations", $this->transformation_string($transformation)];

        return $this->call_api("delete", $uri, [], $options);
    }

    function update_transformation($transformation, $updates = [], $options = []) {
        $uri = ["transformations", $this->transformation_string($transformation)];

        return $this->call_api("put", $uri, $updates, $options);
    }

    function create_transformation($name, $definition, $options = []) {
        $uri = ["transformations", $name];

        return $this->call_api("post", $uri, ["transformation" => $this->transformation_string($definition)], $options);
    }
}

?>
