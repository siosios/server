<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2023 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCP\Authentication\Token;

use JsonSerializable;

/**
 * @since 28.0.0
 */
interface IToken extends JsonSerializable {
	/**
	 * @since 28.0.0
	 */
	public const TEMPORARY_TOKEN = 0;
	/**
	 * @since 28.0.0
	 */
	public const PERMANENT_TOKEN = 1;
	/**
	 * @since 28.0.0
	 */
	public const WIPE_TOKEN = 2;
	/**
	 * @since 28.0.0
	 */
	public const DO_NOT_REMEMBER = 0;
	/**
	 * @since 28.0.0
	 */
	public const REMEMBER = 1;

	/**
	 * Get the token ID
	 * @since 28.0.0
	 */
	public function getId(): int;

	/**
	 * Get the user UID
	 * @since 28.0.0
	 */
	public function getUID(): string;

	/**
	 * Get the login name used when generating the token
	 * @since 28.0.0
	 */
	public function getLoginName(): string;

	/**
	 * Get the (encrypted) login password
	 * @since 28.0.0
	 */
	public function getPassword(): ?string;

	/**
	 * Get the timestamp of the last password check
	 * @since 28.0.0
	 */
	public function getLastCheck(): int;

	/**
	 * Set the timestamp of the last password check
	 * @since 28.0.0
	 */
	public function setLastCheck(int $time): void;

	/**
	 * Get the authentication scope for this token
	 * @since 28.0.0
	 */
	public function getScope(): string;

	/**
	 * Get the authentication scope for this token
	 * @since 28.0.0
	 */
	public function getScopeAsArray(): array;

	/**
	 * Set the authentication scope for this token
	 * @since 28.0.0
	 */
	public function setScope(array $scope): void;

	/**
	 * Get the name of the token
	 * @since 28.0.0
	 */
	public function getName(): string;

	/**
	 * Get the remember state of the token
	 * @since 28.0.0
	 */
	public function getRemember(): int;

	/**
	 * Set the token
	 * @since 28.0.0
	 */
	public function setToken(string $token): void;

	/**
	 * Set the password
	 * @since 28.0.0
	 */
	public function setPassword(string $password): void;

	/**
	 * Set the expiration time of the token
	 * @since 28.0.0
	 */
	public function setExpires(?int $expires): void;
}
