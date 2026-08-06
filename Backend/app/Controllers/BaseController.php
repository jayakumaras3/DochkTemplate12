<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;


class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ["url", "form", "text", "filesystem", "cookie", "date"];

	/**
	 * Constructor.
	 */
	protected $session;
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();

		$this->session = \Config\Services::session();
		$session = $this->session;
		$language = \Config\Services::language();
		// Determine locale from session or fallback to app default
		$locale = $session->get('locale') ?? $session->get('lang') ?? config('App')->defaultLocale ?? 'en';
		$language->setLocale($locale);
		$this->set_timezone();
		// $request = \Config\Services::request();
		// if ($request->isSecure()) {
		// 	$this->response->setHeader(
		// 		'Strict-Transport-Security',
		// 		'max-age=31536000; includeSubDomains; preload'
		// 	);
		// }

		// Security headers
		// $this->response->setHeader('X-Content-Type-Options', 'nosniff');
		// $this->response->setHeader('X-XSS-Protection', '1; mode=block');
		// $this->response->setHeader('X-Frame-Options', 'DENY');


		// parent::initController already called at top

		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type, Authorization");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");



		if (strtolower($request->getMethod()) === 'options') {
			exit(0);
		}
		// Initialize session
		$this->session = \Config\Services::session();
		$this->session->start(); // optional if sessions are auto-started
	}
	public function set_timezone()
	{
		if (session()->get('id_user')) {
			$db = \Config\Database::connect();
			$builder = $db->table('users as u');
			$builder->select("u.*,t.timezone_pname");
			$builder->join('timezone as t', 't.id_t = u.timezone', 'left');
			$builder->where('u.id_user', session()->get('id_user'));
			$builder->where('u.valid', 1);
			$data = $builder->get();
			if (count($data->getResultArray()) == 1) {
				$userdata = $data->getRowArray();
				if (!empty($userdata['timezone_pname'])) {
					date_default_timezone_set($userdata['timezone_pname']);
				} else {
				}
				//echo date_default_timezone_get();
			} else {
				return false;
			}
		}
	}
	protected function requireLogin(): ?ResponseInterface
	{
		$user = $this->getAuthenticatedUser();

		if ($user === null) {
			return $this->unauthenticatedResponse();
		}

		return null;
	}

	protected function requireRole(array $roles): ?ResponseInterface
	{
		$user = $this->getAuthenticatedUser();

		if ($user === null) {
			return $this->unauthenticatedResponse();
		}

		$userRoles = $this->getAuthenticatedUserRoles($user);

		if (empty(array_intersect($userRoles, $roles))) {
			return $this->forbiddenResponse('You do not have permission.', 'You do not have permission to access this page.');
		}

		return null;
	}

	// Same detail_type -> "where did the user come from" link/label mapping used by
	// My_training::read_more()'s own back link, shared here so every course sub-page
	// (settings, categories, course builder, feedback, assign users, ...) can point its
	// "Courses" breadcrumb link back to wherever the user actually started (My Courses,
	// Marketplace, Learning Plan, Demos, ...) instead of hard-coding SCORM/scorm_courses.
	protected function coursesBreadcrumbLink(): array
	{
		$detailType = $_SESSION['detail_type'] ?? null;
		switch ($detailType) {
			case 1:
				return ['link' => 'my_training', 'label' => lang('Buttons.Dashboard')];
			case 2:
				return ['link' => 'SCORM/Scorm_courses', 'label' => lang('Buttons.My Courses')];
			case 3:
				return ['link' => 'demo/Demo_dashboard', 'label' => lang('Buttons.Demos')];
			case 4:
				return ['link' => 'Game/gamification', 'label' => lang('Buttons.Gamification')];
			case 5:
				return ['link' => 'marketplace/dashboard', 'label' => lang('Buttons.Marketplace')];
			case 6:
				return ['link' => 'category/dashboard/view_courses', 'label' => lang('Buttons.Category Courses')];
			case 7:
				return ['link' => 'SCORM/Scorm_client/reviews', 'label' => lang('Buttons.Client Dashboard')];
			case 8:
				return ['link' => 'my_training/course_group_list', 'label' => lang('Buttons.Course Group')];
			case 9:
				return ['link' => 'marketplace/Learning_dashboard/learning_courses', 'label' => lang('Buttons.Learning Courses')];
			case 10:
				return ['link' => 'marketplace/Learning_dashboard', 'label' => lang('Buttons.Learning Plan Dashboard')];
			case 11:
				return ['link' => 'marketplace/Learning_dashboard/learning_courses', 'label' => lang('UI_Text.Back')];
			case 12:
				return ['link' => '/marketplace/admin/edit_courses', 'label' => lang('Buttons.Courses')];
			case 13:
				return ['link' => 'Certification/Certification_Portal/certificationDetails', 'label' => lang('UI_Text.Back')];
			default:
				return ['link' => 'SCORM/scorm_courses', 'label' => lang('UI_Text.Courses')];
		}
	}

	protected function requirePermission(array $permissions): ?ResponseInterface
	{
		$user = $this->getAuthenticatedUser();

		if ($user === null) {
			return $this->unauthenticatedResponse();
		}

		$userPermissions = $this->getAuthenticatedUserPermissions($user);

		if (empty(array_intersect($userPermissions, $permissions))) {
			return $this->forbiddenResponse('You do not have permission.', 'You do not have permission to access this resource.');
		}

		return null;
	}

	protected function getAuthenticatedUser(): ?array
	{
		$user = session()->get('userData');


		return is_array($user) && !empty($user) ? $user : null;
	}

	protected function getAuthenticatedUserRoles(?array $user = null): array
	{
		$user ??= $this->getAuthenticatedUser();

		if ($user === null) {
			return [];
		}

		$rawRoles = $user['userlevel'] ?? session()->get('userlevel') ?? [];

		if (is_string($rawRoles)) {
			$rawRoles = explode(',', $rawRoles);
		}

		if (!is_array($rawRoles)) {
			$rawRoles = [$rawRoles];
		}

		return array_values(array_unique(array_map('intval', array_filter($rawRoles, static fn($role) => $role !== '' && $role !== null))));
	}

	protected function getAuthenticatedUserPermissions(?array $user = null): array
	{
		$user ??= $this->getAuthenticatedUser();

		if ($user === null) {
			return [];
		}

		$rawPermissions = $user['permissions'] ?? session()->get('permissions') ?? [];

		if (is_string($rawPermissions)) {
			$rawPermissions = array_map('trim', explode(',', $rawPermissions));
		}

		if (!is_array($rawPermissions)) {
			$rawPermissions = [$rawPermissions];
		}

		return array_values(array_unique(array_filter(array_map(static fn($permission) => is_scalar($permission) ? trim((string) $permission) : '', $rawPermissions))));
	}

	protected function unauthenticatedResponse(string $message = 'Session expired. Please login again.', string $errors = 'Please login first.'): ResponseInterface
	{
		if (!$this->expectsJsonResponse()) {
			return $this->redirectToAngularLogin($message);
		}

		return $this->response
			->setStatusCode(401)
			->setHeader('Content-Type', 'application/json')
			->setJSON([
				'success' => false,
				'message' => $message,
				'errors' => $errors,
			]);
	}

	protected function forbiddenResponse(string $message = 'Forbidden.', string $errors = 'You do not have permission to access this resource.'): ResponseInterface
	{
		if (!$this->expectsJsonResponse()) {
			session()->setFlashdata('error', $message);
			return redirect()->to(base_url('my_training'));
		}

		return $this->response
			->setStatusCode(403)
			->setHeader('Content-Type', 'application/json')
			->setJSON([
				'success' => false,
				'message' => $message,
				'errors' => $errors,
			]);
	}

	protected function expectsJsonResponse(): bool
	{
		if ($this->request->isAJAX()) {
			return true;
		}

		$acceptHeader = strtolower((string) $this->request->getHeaderLine('Accept'));
		$contentTypeHeader = strtolower((string) $this->request->getHeaderLine('Content-Type'));
		$requestedWith = strtolower((string) $this->request->getHeaderLine('X-Requested-With'));
		$path = trim((string) $this->request->getUri()->getPath(), '/');

		return str_contains($acceptHeader, 'application/json')
			|| str_contains($contentTypeHeader, 'application/json')
			|| $requestedWith === 'xmlhttprequest'
			|| str_contains($path, 'api/');
	}

	protected function redirectToAngularLogin(string $message): ResponseInterface
	{
		$query = http_build_query([
			'reason' => 'session-expired',
			'message' => $message,
		]);

		return redirect()->to(base_url('ang/login') . '?' . $query);
	}
}
