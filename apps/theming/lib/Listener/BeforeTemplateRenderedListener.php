<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 Morris Jobke <hey@morrisjobke.de>
 *
 * @author Morris Jobke <hey@morrisjobke.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Theming\Listener;

use OCA\Theming\AppInfo\Application;
use OCA\Theming\Service\JSDataService;
use OCA\Theming\Service\ThemeInjectionService;
use OCA\Theming\Service\ThemesService;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\IInitialStateService;
use OCP\IServerContainer;
use OCP\IURLGenerator;

class BeforeTemplateRenderedListener implements IEventListener {

	private IInitialStateService $initialStateService;
	private IServerContainer $serverContainer;
	private ThemeInjectionService $themeInjectionService;

	public function __construct(
		IInitialStateService $initialStateService,
		IServerContainer $serverContainer,
		ThemeInjectionService $themeInjectionService
	) {
		$this->initialStateService = $initialStateService;
		$this->serverContainer = $serverContainer;
		$this->themeInjectionService = $themeInjectionService;
	}

	public function handle(Event $event): void {
		$serverContainer = $this->serverContainer;
		$this->initialStateService->provideLazyInitialState(Application::APP_ID, 'data', function () use ($serverContainer) {
			return $serverContainer->query(JSDataService::class);
		});

		$this->themeInjectionService->injectHeaders();

		// Making sure to inject just after core
		\OCP\Util::addScript('theming', 'theming', 'core');
	}
}
