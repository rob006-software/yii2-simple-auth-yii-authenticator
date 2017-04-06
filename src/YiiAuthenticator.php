<?php

/*
 * This file is part of the yii2-simple-auth-yii-authenticator.
 *
 * Copyright (c) 2016 Robert Korulczyk <robert@korulczyk.pl>.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace rob006\simpleauth;

use yii\base\InvalidParamException;
use yii\httpclient\Request;

/**
 * Helper class for authenticate \yii\httpclient\Request from yiisoft/yii2-httpclient.
 *
 * @see https://github.com/yiisoft/yii2-httpclient
 *
 * @author Robert Korulczyk <robert@korulczyk.pl>
 * @since 1.0.0
 */
class YiiAuthenticator extends Authenticator {

	/**
	 * {@inheritdoc}
	 *
	 * Handle `\yii\httpclient\Request` from `yiisoft/yii2-httpclient`.
	 *
	 * @param Request $request Request object.
	 * @param string $method
	 * @param string $secret Secret key used for generate token. Leave empty to use secret from
	 * config (`Yii::$app->params['simpleauth']['secret']`).
	 * @return Request Authenticated Request object.
	 * @throws InvalidParamException
	 * @see https://github.com/yiisoft/yii2-httpclient
	 */
	public static function authenticate($request, $method = self::METHOD_HEADER, $secret = null) {
		return parent::authenticate($request, $method, $secret);
	}

	/**
	 * {@inheritdoc}
	 *
	 * Require `\yii\httpclient\Request` from yiisoft/yii2-httpclient.
	 *
	 * @see https://github.com/yiisoft/yii2-httpclient
	 */
	protected function validateRequest() {
		if (!($this->request instanceof Request)) {
			throw new InvalidParamException('$request should be instance of \yii\httpclient\Request');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function authenticateByHeader() {
		/* @var $copy Request */
		$copy = clone $this->request;
		return $this->request->addHeaders([
			static::HEADER_NAME => static::generateAuthToken($copy->prepare()->getFullUrl(), $this->secret),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function authenticateByGetParam() {
		/* @var $copy Request */
		$copy = clone $this->request;
		$this->request->setMethod('get');
		return $this->request->setData(array_merge((array) $this->request->data, [
			static::PARAM_NAME => static::generateAuthToken($copy->prepare()->getFullUrl(), $this->secret),
		]));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function authenticateByPostParam() {
		$this->request->setMethod('post');
		/* @var $copy Request */
		$copy = clone $this->request;
		return $this->request->setData(array_merge((array) $this->request->data, [
			static::PARAM_NAME => static::generateAuthToken($copy->prepare()->getFullUrl(), $this->secret),
		]));
	}

}
