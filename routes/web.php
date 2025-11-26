<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentCategoryController;
use App\Http\Controllers\EquipmentRequestController;
use App\Http\Controllers\AccountPayableController;
use App\Http\Controllers\AccountReceivableController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\FinancialDashboardController;
use App\Http\Controllers\ProjectFinancialBalanceController;
use App\Http\Controllers\TechnicalInspectionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notificações
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::get('/api/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('notifications.recent');
    Route::get('/api/notifications/sounds', [\App\Http\Controllers\NotificationController::class, 'availableSounds'])->name('notifications.sounds');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/test', [\App\Http\Controllers\NotificationController::class, 'sendTest'])->name('notifications.test');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Produtos - Criação (deve vir antes das rotas com parâmetros)
    Route::middleware(['permission:create products'])->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::post('products/generate-sku', [ProductController::class, 'generateSKU'])->name('products.generate-sku');
    });

    // Produtos - Visualização (todos autenticados)
    Route::middleware(['permission:view products'])->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
    });

    // Produtos - Edição/Exclusão (apenas admin/manager)
    Route::middleware(['permission:edit products'])->group(function () {
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    });

    Route::middleware(['permission:delete products'])->group(function () {
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Categorias - Aninhadas em Products
    // Categorias - Criação (deve vir antes das rotas com parâmetros)
    Route::middleware(['permission:create categories'])->prefix('products/categories')->name('categories.')->group(function () {
        Route::get('create', [CategoryController::class, 'create'])->name('create');
        Route::post('', [CategoryController::class, 'store'])->name('store');
    });

    // Categorias - Visualização
    Route::middleware(['permission:view categories'])->prefix('products/categories')->name('categories.')->group(function () {
        Route::get('', [CategoryController::class, 'index'])->name('index');
        Route::get('{category}', [CategoryController::class, 'show'])->name('show');
    });

    // Categorias - Edição/Exclusão (apenas admin/manager)
    Route::middleware(['permission:edit categories'])->prefix('products/categories')->name('categories.')->group(function () {
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('{category}', [CategoryController::class, 'update'])->name('update');
    });

    Route::middleware(['permission:delete categories'])->prefix('products/categories')->name('categories.')->group(function () {
        Route::delete('{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Requisições de Material - Visualização e criação
    Route::middleware(['permission:view service-orders'])->group(function () {
    Route::get('material-requests', [MaterialRequestController::class, 'index'])->name('material-requests.index');
        Route::get('material-requests/{materialRequest}', [MaterialRequestController::class, 'show'])
            ->whereNumber('materialRequest')
            ->name('material-requests.show');
        Route::get('material-requests/{materialRequest}/pdf', [MaterialRequestController::class, 'generatePDF'])
            ->whereNumber('materialRequest')
            ->name('material-requests.pdf');
    });

    Route::middleware(['permission:create service-orders'])->group(function () {
    Route::get('material-requests/create', [MaterialRequestController::class, 'create'])->name('material-requests.create');
    Route::post('material-requests', [MaterialRequestController::class, 'store'])->name('material-requests.store');
    });

    // Requisições de Material - Edição/Exclusão (apenas admin/manager)
    Route::middleware(['permission:edit service-orders'])->group(function () {
    Route::get('material-requests/{materialRequest}/edit', [MaterialRequestController::class, 'edit'])
            ->whereNumber('materialRequest')
            ->name('material-requests.edit');
    Route::put('material-requests/{materialRequest}', [MaterialRequestController::class, 'update'])
            ->whereNumber('materialRequest')
            ->name('material-requests.update');
        Route::post('material-requests/{materialRequest}/complete', [MaterialRequestController::class, 'complete'])
            ->whereNumber('materialRequest')
            ->name('material-requests.complete');
    });

    Route::middleware(['permission:delete service-orders'])->group(function () {
    Route::delete('material-requests/{materialRequest}', [MaterialRequestController::class, 'destroy'])
            ->whereNumber('materialRequest')
            ->name('material-requests.destroy');
    });

    // Fornecedores - Rotas específicas primeiro (antes das rotas com parâmetros)
    // Fornecedores - Criação
    Route::middleware(['permission:create suppliers'])->group(function () {
        Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::post('suppliers/fetch-cnpj', [SupplierController::class, 'fetchCNPJ'])->name('suppliers.fetch-cnpj');
    });

    // Categorias de Fornecedores - DEVE VIR ANTES das rotas suppliers/{supplier}
    // Categorias de Fornecedores - Criação
    Route::middleware(['permission:create suppliers'])->prefix('suppliers/categories')->name('supplier-categories.')->group(function () {
        Route::get('create', [SupplierCategoryController::class, 'create'])->name('create');
        Route::post('', [SupplierCategoryController::class, 'store'])->name('store');
    });

    // Categorias de Fornecedores - Visualização
    Route::middleware(['permission:view suppliers'])->prefix('suppliers/categories')->name('supplier-categories.')->group(function () {
        Route::get('', [SupplierCategoryController::class, 'index'])->name('index');
    });

    // Categorias de Fornecedores - Edição/Exclusão
    Route::middleware(['permission:edit suppliers'])->prefix('suppliers/categories')->name('supplier-categories.')->group(function () {
        Route::get('{supplierCategory}/edit', [SupplierCategoryController::class, 'edit'])->name('edit');
        Route::put('{supplierCategory}', [SupplierCategoryController::class, 'update'])->name('update');
    });

    Route::middleware(['permission:delete suppliers'])->prefix('suppliers/categories')->name('supplier-categories.')->group(function () {
        Route::delete('{supplierCategory}', [SupplierCategoryController::class, 'destroy'])->name('destroy');
    });

    // Fornecedores - Visualização
    Route::middleware(['permission:view suppliers'])->group(function () {
        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    });

    // Fornecedores - Edição/Exclusão (apenas admin/manager)
    Route::middleware(['permission:edit suppliers'])->group(function () {
        Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    });

    Route::middleware(['permission:delete suppliers'])->group(function () {
        Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    Route::middleware(['permission:create employees'])->group(function () {
        Route::get('employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    });

    // Funcionários - Visualização
    Route::middleware(['permission:view employees'])->group(function () {
        Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    });

    Route::middleware(['permission:edit employees'])->group(function () {
        Route::get('employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::post('employees/{employee}/photos', [EmployeeController::class, 'storePhoto'])->name('employees.photos.store');
        Route::delete('employees/{employee}/photos', [EmployeeController::class, 'destroyPhoto'])->name('employees.photos.destroy');
        Route::post('employees/{employee}/deductions', [EmployeeController::class, 'storeDeduction'])->name('employees.deductions.store');
        Route::delete('employees/{employee}/deductions/{deduction}', [EmployeeController::class, 'destroyDeduction'])->name('employees.deductions.destroy');
        Route::post('employees/{employee}/documents', [EmployeeController::class, 'storeDocument'])->name('employees.documents.store');
        Route::delete('employees/{employee}/documents/{document}', [EmployeeController::class, 'destroyDocument'])->name('employees.documents.destroy');
    });

    Route::middleware(['permission:delete employees'])->group(function () {
        Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    // Propostas de Funcionários
    Route::middleware(['permission:view employees'])->group(function () {
        Route::get('proposals', [\App\Http\Controllers\EmployeeProposalController::class, 'index'])->name('proposals.index');
        Route::get('employees/{employee}/proposals', [\App\Http\Controllers\EmployeeProposalController::class, 'index'])->name('employees.proposals.index');
        Route::get('employees/{employee}/proposals/create', [\App\Http\Controllers\EmployeeProposalController::class, 'create'])->name('employees.proposals.create');
        Route::post('employees/{employee}/proposals', [\App\Http\Controllers\EmployeeProposalController::class, 'store'])->name('employees.proposals.store');
        Route::get('employees/{employee}/proposals/{proposal}', [\App\Http\Controllers\EmployeeProposalController::class, 'show'])->name('employees.proposals.show');
    });

    // Visualização pública de propostas (sem autenticação)
    Route::get('proposals/view/{token}', [\App\Http\Controllers\EmployeeProposalController::class, 'viewByToken'])->name('proposals.view');
    Route::post('proposals/{token}/accept', [\App\Http\Controllers\EmployeeProposalController::class, 'accept'])->name('proposals.accept');
    Route::post('proposals/{token}/reject', [\App\Http\Controllers\EmployeeProposalController::class, 'reject'])->name('proposals.reject');

    // Ponto (Attendance) - apenas funcionários
    Route::middleware(['role:employee'])->group(function () {
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance/punch', [AttendanceController::class, 'punch'])->name('attendance.punch');
    });

    // Utilitário de geocodificação (autenticado)
    Route::get('attendance/reverse-geocode', [AttendanceController::class, 'reverseGeocode'])->name('attendance.reverse-geocode');

    // Gestão de Pontos - gerente/admin
    Route::middleware(['role:manager|admin'])->group(function () {
        Route::get('attendance/manage', [AttendanceController::class, 'manage'])->name('attendance.manage');
        Route::get('attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
        Route::get('attendance/employee/{employee}/report', [AttendanceController::class, 'employeeReport'])->name('attendance.employee.report');
        Route::get('attendance/employee/{employee}/pdf', [AttendanceController::class, 'generateEmployeePDF'])->name('attendance.employee.pdf');
    });

    // Gestão de Permissões - admin/gerente (roles e permissions)
    Route::middleware(['permission:manage permissions'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('permissions/users', [PermissionController::class, 'users'])->name('permissions.users');
        Route::post('permissions/users/{user}/roles', [PermissionController::class, 'syncUserRoles'])->name('permissions.users.roles');

        Route::get('permissions/roles', [PermissionController::class, 'roles'])->name('permissions.roles');
        Route::post('permissions/roles/{role}/permissions', [PermissionController::class, 'syncRolePermissions'])->name('permissions.roles.permissions');

        // System Settings
        Route::get('settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
        Route::post('settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');

        // Email Settings
        Route::get('email', [\App\Http\Controllers\AdminController::class, 'emailSettings'])->name('email.index');
        Route::post('email', [\App\Http\Controllers\AdminController::class, 'updateEmailSettings'])->name('email.update');
        Route::post('email/test', [\App\Http\Controllers\AdminController::class, 'sendTestEmail'])->name('email.test');
    });

    // Movimentações de Estoque (apenas admin/manager)
    Route::middleware(['permission:manage stock'])->group(function () {
    Route::resource('stock-movements', StockMovementController::class);
    });

    // Categorias de Equipamentos - DEVE VIR ANTES das rotas equipment/{equipment}
    Route::middleware(['permission:create products'])->prefix('equipment/categories')->name('equipment-categories.')->group(function () {
        Route::get('create', [EquipmentCategoryController::class, 'create'])->name('create');
        Route::post('', [EquipmentCategoryController::class, 'store'])->name('store');
    });

    Route::middleware(['permission:view products'])->prefix('equipment/categories')->name('equipment-categories.')->group(function () {
        Route::get('', [EquipmentCategoryController::class, 'index'])->name('index');
    });

    Route::middleware(['permission:edit products'])->prefix('equipment/categories')->name('equipment-categories.')->group(function () {
        Route::get('{equipmentCategory}/edit', [EquipmentCategoryController::class, 'edit'])->name('edit');
        Route::put('{equipmentCategory}', [EquipmentCategoryController::class, 'update'])->name('update');
    });

    Route::middleware(['permission:delete products'])->prefix('equipment/categories')->name('equipment-categories.')->group(function () {
        Route::delete('{equipmentCategory}', [EquipmentCategoryController::class, 'destroy'])->name('destroy');
    });

    // Equipamentos - Criação/Edição (apenas admin/manager) - DEVE VIR ANTES DAS ROTAS COM PARÂMETROS
    Route::middleware(['permission:create products'])->group(function () {
        Route::get('equipment/create', [EquipmentController::class, 'create'])->name('equipment.create');
        Route::post('equipment', [EquipmentController::class, 'store'])->name('equipment.store');
    });

    // Equipamentos - Visualização (todos autenticados)
    Route::middleware(['permission:view products'])->group(function () {
        Route::get('equipment', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('equipment/{equipment}', [EquipmentController::class, 'show'])->name('equipment.show');
        Route::get('equipment/{equipment}/history', [EquipmentController::class, 'history'])->name('equipment.history');
    });

    Route::middleware(['permission:edit products'])->group(function () {
        Route::get('equipment/{equipment}/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::put('equipment/{equipment}', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('equipment/{equipment}/photos/{photoIndex}', [EquipmentController::class, 'deletePhoto'])->name('equipment.photos.delete');
    });

    Route::middleware(['permission:delete products'])->group(function () {
        Route::delete('equipment/{equipment}', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
    });

    // Requisições de Equipamento - Visualização e criação
    Route::middleware(['permission:view service-orders'])->group(function () {
        Route::get('equipment-requests', [EquipmentRequestController::class, 'index'])->name('equipment-requests.index');
        Route::get('equipment-requests/{equipmentRequest}', [EquipmentRequestController::class, 'show'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.show');
        Route::get('equipment-requests/{equipmentRequest}/pdf', [EquipmentRequestController::class, 'generatePDF'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.pdf');
    });

    Route::middleware(['permission:create service-orders'])->group(function () {
        Route::get('equipment-requests/create', [EquipmentRequestController::class, 'create'])->name('equipment-requests.create');
        Route::post('equipment-requests', [EquipmentRequestController::class, 'store'])->name('equipment-requests.store');
    });

    // Requisições de Equipamento - Edição/Exclusão (apenas admin/manager)
    Route::middleware(['permission:edit service-orders'])->group(function () {
        Route::get('equipment-requests/{equipmentRequest}/edit', [EquipmentRequestController::class, 'edit'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.edit');
        Route::put('equipment-requests/{equipmentRequest}', [EquipmentRequestController::class, 'update'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.update');
        Route::post('equipment-requests/{equipmentRequest}/approve', [EquipmentRequestController::class, 'approve'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.approve');
        Route::post('equipment-requests/{equipmentRequest}/reject', [EquipmentRequestController::class, 'reject'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.reject');
        Route::post('equipment-requests/{equipmentRequest}/complete', [EquipmentRequestController::class, 'complete'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.complete');
    });

    Route::middleware(['permission:delete service-orders'])->group(function () {
        Route::delete('equipment-requests/{equipmentRequest}', [EquipmentRequestController::class, 'destroy'])
            ->whereNumber('equipmentRequest')
            ->name('equipment-requests.destroy');
    });

    // Vistorias Técnicas
    Route::middleware(['permission:view service-orders'])->group(function () {
        Route::get('technical-inspections', [TechnicalInspectionController::class, 'index'])->name('technical-inspections.index');
        Route::get('technical-inspections/{technicalInspection}', [TechnicalInspectionController::class, 'show'])
            ->whereNumber('technicalInspection')
            ->name('technical-inspections.show');
        Route::get('technical-inspections/{technicalInspection}/pdf', [TechnicalInspectionController::class, 'generatePDF'])
            ->whereNumber('technicalInspection')
            ->name('technical-inspections.pdf');
        Route::get('technical-inspections/{technicalInspection}/view-pdf', [TechnicalInspectionController::class, 'viewPDF'])
            ->whereNumber('technicalInspection')
            ->name('technical-inspections.view-pdf');
    });

    Route::middleware(['permission:edit service-orders'])->group(function () {
        Route::get('technical-inspections/create', [TechnicalInspectionController::class, 'create'])->name('technical-inspections.create');
        Route::post('technical-inspections', [TechnicalInspectionController::class, 'store'])->name('technical-inspections.store');
        Route::get('technical-inspections/{technicalInspection}/edit', [TechnicalInspectionController::class, 'edit'])
            ->whereNumber('technicalInspection')
            ->name('technical-inspections.edit');
        Route::put('technical-inspections/{technicalInspection}', [TechnicalInspectionController::class, 'update'])
            ->whereNumber('technicalInspection')
            ->name('technical-inspections.update');
        Route::delete('technical-inspections/{technicalInspection}', [TechnicalInspectionController::class, 'destroy'])
            ->whereNumber('technicalInspection')
            ->name('technical-inspections.destroy');
    });

    // Relatórios (apenas admin/manager)
    Route::middleware(['permission:view reports'])->group(function () {
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/movements', [ReportController::class, 'movements'])->name('reports.movements');
    Route::get('reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
    Route::get('reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    });

    // Mapa de Obras
    Route::middleware(['role_or_permission:manager|admin|view projects'])->group(function () {
        Route::get('map', [\App\Http\Controllers\MapController::class, 'index'])->name('map.index');
    });

    // Obras / Projects
    Route::middleware(['role_or_permission:manager|admin|view projects'])->group(function () {
        Route::get('projects', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');

        // Balanço Financeiro do Projeto
        Route::middleware(['permission:manage finances'])->group(function () {
            Route::get('projects/{project}/financial-balance', [ProjectFinancialBalanceController::class, 'show'])->name('projects.financial-balance');
            Route::post('projects/{project}/financial-balance/sync', [ProjectFinancialBalanceController::class, 'sync'])->name('projects.financial-balance.sync');
        });
    });
    Route::middleware(['role_or_permission:manager|admin|create projects'])->group(function () {
        Route::get('projects/create', [\App\Http\Controllers\ProjectController::class, 'create'])->name('projects.create');
        Route::post('projects', [\App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
    });
    Route::middleware(['role_or_permission:manager|admin|edit projects'])->group(function () {
        Route::get('projects/{project}/edit', [\App\Http\Controllers\ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('projects/{project}', [\App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
    });
    Route::middleware(['role_or_permission:manager|admin|delete projects'])->group(function () {
        Route::delete('projects/{project}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    // Atualizações e Fotos de Obras
    Route::middleware(['role_or_permission:manager|admin|post project-updates'])->group(function () {
        Route::post('projects/{project}/updates', [\App\Http\Controllers\ProjectController::class, 'storeUpdate'])->name('projects.updates.store');
        Route::post('projects/{project}/photos', [\App\Http\Controllers\ProjectController::class, 'uploadPhoto'])->name('projects.photos.upload');
        Route::post('projects/{project}/files', [\App\Http\Controllers\ProjectController::class, 'uploadFile'])->name('projects.files.upload');
    });

    // Download e Delete de Arquivos
    Route::middleware(['role_or_permission:manager|admin|view projects'])->group(function () {
        Route::get('projects/{project}/files/{file}/download', [\App\Http\Controllers\ProjectController::class, 'downloadFile'])->whereNumber('file')->name('projects.files.download');
    });

    Route::delete('projects/{project}/files/{file}', [\App\Http\Controllers\ProjectController::class, 'deleteFile'])->whereNumber('file')->name('projects.files.delete');

    // Project tasks (todos)
    Route::middleware(['role_or_permission:manager|admin|edit projects'])->group(function () {
        Route::post('projects/{project}/tasks', [\App\Http\Controllers\ProjectController::class, 'storeTask'])->name('projects.tasks.store');
        Route::patch('projects/{project}/tasks/{task}/status', [\App\Http\Controllers\ProjectController::class, 'updateTaskStatus'])->whereNumber('task')->name('projects.tasks.status');
        Route::patch('projects/{project}/tasks/{task}', [\App\Http\Controllers\ProjectController::class, 'updateTask'])->whereNumber('task')->name('projects.tasks.update');
        Route::delete('projects/{project}/tasks/{task}', [\App\Http\Controllers\ProjectController::class, 'deleteTask'])->whereNumber('task')->name('projects.tasks.delete');
    });

    // Cliente - Dashboard e Projects (somente leitura)
    Route::middleware(['permission:view client-projects'])->prefix('client')->name('client.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Client\ProjectController::class, 'dashboard'])->name('dashboard');
        Route::get('projects', [\App\Http\Controllers\Client\ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [\App\Http\Controllers\Client\ProjectController::class, 'show'])->name('projects.show');
    });

    // Orçamentos
    Route::middleware(['role_or_permission:manager|admin|view budgets'])->group(function () {
        Route::get('budgets', [\App\Http\Controllers\ProjectController::class, 'budgetsIndex'])->name('budgets.index');
        Route::get('budgets/{budget}/pdf', [\App\Http\Controllers\ProjectController::class, 'budgetsPdf'])->whereNumber('budget')->name('budgets.pdf');
    });
    Route::middleware(['role_or_permission:manager|admin|manage budgets'])->group(function () {
        Route::get('budgets/create', [\App\Http\Controllers\ProjectController::class, 'budgetsCreate'])->name('budgets.create');
        Route::post('budgets', [\App\Http\Controllers\ProjectController::class, 'budgetsStore'])->name('budgets.store');
        Route::get('budgets/{budget}/edit', [\App\Http\Controllers\ProjectController::class, 'budgetsEdit'])->whereNumber('budget')->name('budgets.edit');
        Route::put('budgets/{budget}', [\App\Http\Controllers\ProjectController::class, 'budgetsUpdate'])->whereNumber('budget')->name('budgets.update');
        Route::patch('budgets/{budget}/approve', [\App\Http\Controllers\ProjectController::class, 'budgetsApprove'])->whereNumber('budget')->name('budgets.approve');
        Route::patch('budgets/{budget}/reject', [\App\Http\Controllers\ProjectController::class, 'budgetsReject'])->whereNumber('budget')->name('budgets.reject');
        Route::patch('budgets/{budget}/cancel', [\App\Http\Controllers\ProjectController::class, 'budgetsCancel'])->whereNumber('budget')->name('budgets.cancel');
    });

    // Service Management Routes
    Route::middleware(['role_or_permission:manager|admin|manage services'])->group(function () {
        // Service Categories
        Route::resource('service-categories', \App\Http\Controllers\ServiceCategoryController::class);

        // Services
        Route::resource('services', \App\Http\Controllers\ServiceController::class);

        // Labor Types
        Route::resource('labor-types', \App\Http\Controllers\LaborTypeController::class);
    });

    // Clients Routes
    // IMPORTANTE: A rota 'create' deve vir ANTES da rota '{client}' para evitar conflito de rotas
    Route::middleware(['role_or_permission:manager|admin|create clients'])->group(function () {
        Route::get('clients/create', [\App\Http\Controllers\ClientController::class, 'create'])->name('clients.create');
        Route::post('clients', [\App\Http\Controllers\ClientController::class, 'store'])->name('clients.store');
    });
    Route::middleware(['role_or_permission:manager|admin|view clients'])->group(function () {
        Route::get('clients', [\App\Http\Controllers\ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/{client}', [\App\Http\Controllers\ClientController::class, 'show'])->name('clients.show');
    });
    Route::middleware(['role_or_permission:manager|admin|edit clients'])->group(function () {
        Route::get('clients/{client}/edit', [\App\Http\Controllers\ClientController::class, 'edit'])->name('clients.edit');
        Route::put('clients/{client}', [\App\Http\Controllers\ClientController::class, 'update'])->name('clients.update');
    });
    Route::middleware(['role_or_permission:manager|admin|delete clients'])->group(function () {
        Route::delete('clients/{client}', [\App\Http\Controllers\ClientController::class, 'destroy'])->name('clients.destroy');
    });

    // Contracts Routes
    // IMPORTANTE: A rota 'create' deve vir ANTES da rota '{contract}' para evitar conflito de rotas
    Route::middleware(['role_or_permission:manager|admin|create contracts'])->group(function () {
        Route::get('contracts/create', [\App\Http\Controllers\ContractController::class, 'create'])->name('contracts.create');
        Route::post('contracts', [\App\Http\Controllers\ContractController::class, 'store'])->name('contracts.store');
    });
    Route::middleware(['role_or_permission:manager|admin|view contracts'])->group(function () {
        Route::get('contracts', [\App\Http\Controllers\ContractController::class, 'index'])->name('contracts.index');
        Route::get('contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
        Route::get('contracts/{contract}/download', [\App\Http\Controllers\ContractController::class, 'download'])->name('contracts.download');
    });
    Route::middleware(['role_or_permission:manager|admin|edit contracts'])->group(function () {
        Route::get('contracts/{contract}/edit', [\App\Http\Controllers\ContractController::class, 'edit'])->name('contracts.edit');
        Route::put('contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'update'])->name('contracts.update');
    });
    Route::middleware(['role_or_permission:manager|admin|delete contracts'])->group(function () {
        Route::delete('contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.destroy');
    });

    // Inspections Routes
    // IMPORTANTE: A rota 'create' deve vir ANTES da rota '{inspection}' para evitar conflito de rotas
    Route::middleware(['role_or_permission:manager|admin|create inspections'])->group(function () {
        Route::get('inspections/create', [\App\Http\Controllers\InspectionController::class, 'create'])->name('inspections.create');
        Route::post('inspections', [\App\Http\Controllers\InspectionController::class, 'store'])->name('inspections.store');
    });
    Route::middleware(['role_or_permission:manager|admin|view inspections'])->group(function () {
        Route::get('inspections', [\App\Http\Controllers\InspectionController::class, 'index'])->name('inspections.index');
        Route::get('inspections/{inspection}', [\App\Http\Controllers\InspectionController::class, 'show'])->name('inspections.show');
        Route::get('inspections/{inspection}/pdf', [\App\Http\Controllers\InspectionController::class, 'generatePDF'])->name('inspections.pdf');
    });
    Route::middleware(['role_or_permission:manager|admin|edit inspections'])->group(function () {
        Route::get('inspections/{inspection}/edit', [\App\Http\Controllers\InspectionController::class, 'edit'])->name('inspections.edit');
        Route::put('inspections/{inspection}', [\App\Http\Controllers\InspectionController::class, 'update'])->name('inspections.update');
        Route::patch('inspections/{inspection}/approve', [\App\Http\Controllers\InspectionController::class, 'approve'])->name('inspections.approve');
        Route::post('inspections/{inspection}/upload-signed', [\App\Http\Controllers\InspectionController::class, 'uploadSignedDocument'])->name('inspections.upload-signed');
    });
    Route::middleware(['role_or_permission:manager|admin|delete inspections'])->group(function () {
        Route::delete('inspections/{inspection}', [\App\Http\Controllers\InspectionController::class, 'destroy'])->name('inspections.destroy');
    });

    // Client Documents Routes
    Route::middleware(['role_or_permission:manager|admin|edit clients'])->group(function () {
        Route::post('clients/{client}/documents', [\App\Http\Controllers\ClientDocumentController::class, 'store'])->name('clients.documents.store');
        Route::delete('client-documents/{clientDocument}', [\App\Http\Controllers\ClientDocumentController::class, 'destroy'])->name('client-documents.destroy');
    });

    // API Routes for Search
    Route::middleware(['auth'])->prefix('api')->group(function () {
        Route::get('services/search', [\App\Http\Controllers\ServiceController::class, 'search'])->name('api.services.search');
        Route::get('labor-types/search', [\App\Http\Controllers\LaborTypeController::class, 'search'])->name('api.labor-types.search');
        Route::get('clients/fetch-cnpj', [\App\Http\Controllers\ClientController::class, 'fetchCnpj'])->name('api.clients.fetch-cnpj');
        Route::get('clients/{client}/last-inspection', [\App\Http\Controllers\InspectionController::class, 'getLastInspection'])->name('api.clients.last-inspection');
    });

    // Financeiro - Sistema Financeiro
    Route::middleware(['permission:manage finances'])->prefix('financial')->name('financial.')->group(function () {
        // Dashboard Financeiro
        Route::get('dashboard', [FinancialDashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard/data', [FinancialDashboardController::class, 'getFinancialData'])->name('dashboard.data');

        // Contas a Pagar
        Route::resource('accounts-payable', AccountPayableController::class);

        // Contas a Receber
        Route::resource('accounts-receivable', AccountReceivableController::class);

        // Notas Fiscais
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');

        // Recibos
        Route::resource('receipts', ReceiptController::class);
        Route::get('receipts/{receipt}/pdf', [ReceiptController::class, 'generatePDF'])->name('receipts.pdf');
    });
});

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function () {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    });
});

Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// Service Worker para PWA
Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw.js')), 200)
        ->header('Content-Type', 'application/javascript')
        ->header('Service-Worker-Allowed', '/');
});

// Manifest PWA dinâmico
Route::get('/manifest.json', [App\Http\Controllers\PwaController::class, 'manifest']);
