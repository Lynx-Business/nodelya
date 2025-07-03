export type AccountingPeriodFormProps = {
    team: TeamListResource;
    accountingPeriod?: AccountingPeriodResource;
};
export type AccountingPeriodFormRequest = {
    starts_at: string;
    ends_at: string;
};
export type AccountingPeriodIndexProps = {
    request: AccountingPeriodIndexRequest;
    team: TeamListResource;
    accountingPeriods?: {
        data: Array<AccountingPeriodResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type AccountingPeriodIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
};
export type AccountingPeriodOneOrManyRequest = {
    accounting_period?: number;
    ids?: Array<number>;
};
export type AccountingPeriodResource = {
    id: number;
    label: string;
    starts_at: string;
    ends_at: string;
    deleted_at?: string;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
};
export type AddressData = {
    address: string;
    address_complement?: string;
    city: string;
    postal_code: string;
    country: string;
};
export type BannerAdminFormProps = {
    banner?: BannerAdminFormResource;
};
export type BannerAdminFormRequest = {
    name: string;
    message: string;
    action?: string;
    is_enabled: boolean;
};
export type BannerAdminFormResource = {
    id: number;
    name: string;
    message: string;
    action?: string;
    is_enabled: boolean;
};
export type BannerAdminIndexProps = {
    banners?: {
        data: Array<BannerAdminIndexResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
};
export type BannerAdminIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    is_enabled?: boolean;
};
export type BannerAdminIndexResource = {
    id: number;
    name: string;
    is_enabled: boolean;
};
export type BannerAppResource = {
    id: number;
    name: string;
    message: string;
    action?: string;
};
export type BannerOneOrManyRequest = {
    banner?: number;
    ids?: Array<number>;
};
export type ClientFormProps = {
    client?: ClientFormResource;
};
export type ClientFormRequest = {
    client?: any;
    name: string;
    address: AddressData;
};
export type ClientFormResource = {
    id: number;
    name: string;
    address: AddressData;
};
export type ClientIndexProps = {
    request: ClientIndexRequest;
    clients?: {
        data: Array<ClientIndexResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type ClientIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
};
export type ClientIndexResource = {
    id: number;
    name: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
};
export type ClientOneOrManyRequest = {
    client?: number;
    ids?: Array<number>;
};
export type ConfirmPasswordProps = {};
export type ConfirmPasswordRequest = {
    password: string;
};
export type DashboardAdminIndexProps = {};
export type DashboardIndexProps = {};
export type DealScheduleData = {};
export type DealStatus = 'created' | 'validated' | 'finished';
export type EditAppearanceSettingsProps = {};
export type EditProfileSettingsProps = {
    mustVerifyEmail: boolean;
};
export type EditSecuritySettingsProps = {};
export type EmployeeExpenseBudgetFormProps = {
    accountingPeriod?: AccountingPeriodResource;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
    employee: EmployeeResource;
    expenseBudget?: ExpenseBudgetResource;
};
export type EmployeeExpenseBudgetIndexProps = {
    request: ExpenseBudgetIndexRequest;
    employee: EmployeeResource;
    expenseBudgets?: {
        data: Array<ExpenseBudgetResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    accountingPeriods?: Array<AccountingPeriodResource>;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
};
export type EmployeeExpenseChargeFormProps = {
    accountingPeriod?: AccountingPeriodResource;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
    employee: EmployeeResource;
    expenseCharge?: ExpenseChargeResource;
};
export type EmployeeExpenseChargeIndexProps = {
    request: ExpenseChargeIndexRequest;
    employee: EmployeeResource;
    expenseCharges?: {
        data: Array<ExpenseChargeResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    accountingPeriods?: Array<AccountingPeriodResource>;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
};
export type EmployeeFormProps = {
    projectDepartments?: Array<ProjectDepartmentResource>;
    employee?: EmployeeResource;
};
export type EmployeeFormRequest = {
    project_department_id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    starts_at: string;
};
export type EmployeeIndexProps = {
    request: EmployeeIndexRequest;
    employees?: {
        data: Array<EmployeeResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    accountingPeriods?: Array<AccountingPeriodResource>;
    projectDepartments?: Array<ProjectDepartmentResource>;
};
export type EmployeeIndexRequest = {
    accounting_period?: AccountingPeriodResource;
    project_departments?: Array<ProjectDepartmentResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    accounting_period_id?: number;
    project_department_ids?: null | Array<number>;
};
export type EmployeeOneOrManyRequest = {
    employee?: number;
    ids?: Array<number>;
};
export type EmployeeResource = {
    id: number;
    project_department_id?: number;
    first_name: string;
    last_name: string;
    full_name: string;
    phone: string;
    email: string;
    starts_at: string;
    ends_at?: string;
    deleted_at?: string;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
    project_department?: ProjectDepartmentResource;
};
export type ExpenseBudgetFormProps = {
    accountingPeriod?: AccountingPeriodResource;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
    expenseBudget?: ExpenseBudgetResource;
};
export type ExpenseBudgetFormRequest = {
    model_type?: 'contractor' | 'employee';
    model_id?: number;
    expense_item_id: number;
    amount: number;
};
export type ExpenseBudgetIndexProps = {
    request: ExpenseBudgetIndexRequest;
    expenseBudgets?: {
        data: Array<ExpenseBudgetResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    accountingPeriods?: Array<AccountingPeriodResource>;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
};
export type ExpenseBudgetIndexRequest = {
    accounting_period?: AccountingPeriodResource;
    expense_categories?: Array<ExpenseCategoryResource>;
    expense_sub_categories?: Array<ExpenseSubCategoryResource>;
    expense_items?: Array<ExpenseItemResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    accounting_period_id?: number;
    expense_category_ids?: null | Array<number>;
    expense_sub_category_ids?: null | Array<number>;
    expense_item_ids?: null | Array<number>;
};
export type ExpenseBudgetOneOrManyRequest = {
    expense_budget?: number;
    ids?: Array<number>;
};
export type ExpenseBudgetResource = {
    id: number;
    expense_item_id: number;
    model_type?: 'contractor' | 'employee';
    model_id?: number;
    amount: number;
    starts_at: string;
    ends_at: string;
    deleted_at?: string;
    type?: ExpenseType;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
    expense_item?: ExpenseItemResource;
};
export type ExpenseCategoryFormProps = {
    team: TeamListResource;
    expenseType: ExpenseType;
    expenseCategory?: ExpenseCategoryResource;
};
export type ExpenseCategoryFormRequest = {
    name: string;
};
export type ExpenseCategoryIndexProps = {
    request: ExpenseCategoryIndexRequest;
    team: TeamListResource;
    expenseTypes?: Array<{ value: ExpenseType; label: string }>;
    expenseType: ExpenseType;
    expenseCategories?: {
        data: Array<ExpenseCategoryResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type ExpenseCategoryIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
};
export type ExpenseCategoryOneOrManyRequest = {
    expense_category?: number;
    ids?: Array<number>;
};
export type ExpenseCategoryResource = {
    id: number;
    type: ExpenseType;
    name: string;
    deleted_at?: string;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
    expense_sub_categories?: Array<ExpenseSubCategoryResource>;
};
export type ExpenseChargeFormProps = {
    accountingPeriod?: AccountingPeriodResource;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
    expenseCharge?: ExpenseChargeResource;
};
export type ExpenseChargeFormRequest = {
    model_type?: 'contractor' | 'employee';
    model_id?: number;
    expense_item_id: number;
    amount: number;
    charged_at: string;
};
export type ExpenseChargeIndexProps = {
    request: ExpenseChargeIndexRequest;
    expenseCharges?: {
        data: Array<ExpenseChargeResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    accountingPeriods?: Array<AccountingPeriodResource>;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItems?: Array<ExpenseItemResource>;
};
export type ExpenseChargeIndexRequest = {
    accounting_period?: AccountingPeriodResource;
    expense_categories?: Array<ExpenseCategoryResource>;
    expense_sub_categories?: Array<ExpenseSubCategoryResource>;
    expense_items?: Array<ExpenseItemResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    accounting_period_id?: number;
    expense_category_ids?: null | Array<number>;
    expense_sub_category_ids?: null | Array<number>;
    expense_item_ids?: null | Array<number>;
};
export type ExpenseChargeOneOrManyRequest = {
    expense_charge?: number;
    ids?: Array<number>;
};
export type ExpenseChargeResource = {
    id: number;
    model_type?: 'contractor' | 'employee';
    model_id?: number;
    expense_item_id: number;
    amount: number;
    charged_at: string;
    deleted_at?: string;
    type?: ExpenseType;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
    expense_item?: ExpenseItemResource;
};
export type ExpenseItemFormProps = {
    team: TeamListResource;
    expenseType: ExpenseType;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
    expenseItem?: ExpenseItemResource;
};
export type ExpenseItemFormRequest = {
    expense_category_id: number;
    expense_sub_category_id?: number;
    name: string;
};
export type ExpenseItemIndexProps = {
    request: ExpenseItemIndexRequest;
    team: TeamListResource;
    expenseTypes?: Array<{ value: ExpenseType; label: string }>;
    expenseType: ExpenseType;
    expenseItems?: {
        data: Array<ExpenseItemResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryResource>;
};
export type ExpenseItemIndexRequest = {
    expense_categories?: Array<ExpenseCategoryResource>;
    expense_sub_categories?: Array<ExpenseSubCategoryResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    expense_category_ids?: Array<number>;
    expense_sub_category_ids?: Array<number>;
};
export type ExpenseItemOneOrManyRequest = {
    expense_item?: number;
    ids?: Array<number>;
};
export type ExpenseItemResource = {
    id: number;
    expense_category_id: number;
    expense_sub_category_id?: number;
    name: string;
    deleted_at?: string;
    type?: ExpenseType;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
    expense_category?: ExpenseCategoryResource;
    expense_sub_category?: ExpenseSubCategoryResource;
};
export type ExpenseSubCategoryFormProps = {
    team: TeamListResource;
    expenseType: ExpenseType;
    expenseCategories?: Array<ExpenseCategoryResource>;
    expenseSubCategory?: ExpenseSubCategoryResource;
};
export type ExpenseSubCategoryFormRequest = {
    expense_category_id: number;
    name: string;
};
export type ExpenseSubCategoryIndexProps = {
    request: ExpenseSubCategoryIndexRequest;
    team: TeamListResource;
    expenseTypes?: Array<{ value: ExpenseType; label: string }>;
    expenseType: ExpenseType;
    expenseSubCategories?: {
        data: Array<ExpenseSubCategoryResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
    expenseCategories?: Array<ExpenseCategoryResource>;
};
export type ExpenseSubCategoryIndexRequest = {
    expense_categories?: Array<ExpenseCategoryResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    expense_category_ids?: Array<number>;
};
export type ExpenseSubCategoryOneOrManyRequest = {
    expense_sub_category?: number;
    ids?: Array<number>;
};
export type ExpenseSubCategoryResource = {
    id: number;
    expense_category_id: number;
    name: string;
    deleted_at?: string;
    type?: ExpenseType;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
    expense_category?: ExpenseCategoryResource;
    expense_items?: Array<ExpenseItemResource>;
};
export type ExpenseType = 'general' | 'employee' | 'contractor';
export type ForgotPasswordProps = {
    status?: string;
};
export type ForgotPasswordRequest = {
    email: string;
};
export type LoginProps = {
    canResetPassword: boolean;
    status?: string;
};
export type LoginRequest = {
    email: string;
    password: string;
    remember?: boolean;
};
export type MediaFormRequest = {
    model_type: string;
    model_id: number;
    collection: string;
    file: File;
};
export type MediaResource = {
    id: number;
    uuid: string;
    url: string;
    custom_properties?: Record<string, any>;
};
export type PermissionListResource = {
    id: number;
    name: string;
    display_name: string;
};
export type PermissionName = 'client' | 'expenses';
export type ProjectDepartmentFormProps = {
    team: TeamListResource;
    projectDepartment?: ProjectDepartmentResource;
};
export type ProjectDepartmentFormRequest = {
    name: string;
};
export type ProjectDepartmentIndexProps = {
    request: ProjectDepartmentIndexRequest;
    team: TeamListResource;
    projectDepartments?: {
        data: Array<ProjectDepartmentResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type ProjectDepartmentIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
};
export type ProjectDepartmentOneOrManyRequest = {
    project_department?: number;
    ids?: Array<number>;
};
export type ProjectDepartmentResource = {
    id: number;
    name: string;
    deleted_at?: string;
    can_view?: boolean;
    can_update?: boolean;
    can_trash?: boolean;
    can_restore?: boolean;
    can_delete?: boolean;
};
export type RegisterProps = {};
export type RegisterRequest = {
    first_name: string;
    last_name: string;
    phone: string;
    email: string;
    password: string;
    password_confirmation: string;
};
export type ResetPasswordProps = {
    token: string;
    email: string;
};
export type ResetPasswordRequest = {
    token: string;
    email: string;
    password: string;
    password_confirmation: string;
};
export type RoleListResource = {
    id: number;
    name: string;
    display_name: string;
};
export type RoleName = 'tester' | 'owner' | 'member' | 'editor';
export type TeamFormProps = {
    team?: TeamFormResource;
};
export type TeamFormRequest = {
    name: string;
    logo?: string;
    settings?: TeamSettingsData;
};
export type TeamFormResource = {
    id: number;
    creator_id?: number;
    logo?: MediaResource;
    name: string;
    settings?: TeamSettingsData;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
};
export type TeamIndexProps = {
    request: TeamIndexRequest;
    teams?: {
        data: Array<TeamIndexResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type TeamIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
};
export type TeamIndexResource = {
    id: number;
    creator_id?: number;
    logo?: MediaResource;
    name: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
};
export type TeamListResource = {
    id: number;
    name: string;
    logo?: MediaResource;
};
export type TeamOneOrManyRequest = {
    team?: number;
    ids?: Array<number>;
};
export type TeamResource = {
    id: number;
    name: string;
    logo?: MediaResource;
    settings?: TeamSettingsData;
};
export type TeamSettingsData = {};
export type ToastMessagesData = {
    info?: string;
    success?: string;
    warning?: string;
    error?: string;
};
export type TrashedFilter = 'only' | 'with';
export type UpdateEmployeeEndsAtRequest = {
    ends_at: string;
};
export type UpdatePasswordSettingsRequest = {
    current_password: string;
    password: string;
    password_confirmation: string;
};
export type UpdateProfileSettingsRequest = {
    first_name: string;
    last_name: string;
    phone?: string;
    email: string;
    avatar?: string;
};
export type UserAbilitiesResource = {
    employees: { view_any: boolean; create: boolean };
    expenses: { budgets: { view_any: boolean; create: boolean }; charges: { view_any: boolean; create: boolean } };
    teams: { view_any: boolean; create: boolean };
    users: { view_any: boolean; create: boolean };
};
export type UserListResource = {
    id: number;
    full_name: string;
};
export type UserMemberFormProps = {
    user?: UserMemberFormResource;
    teams?: Array<TeamListResource>;
    permissions?: Array<{ value: PermissionName; label: string }>;
};
export type UserMemberFormRequest = {
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    password?: string;
    password_confirmation?: string;
    avatar?: string;
    team_roles: Array<UserMemberFormTeamRoleData>;
    team_permissions: Array<UserMemberFormTeamPermissionData>;
};
export type UserMemberFormResource = {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    avatar?: MediaResource;
    team_roles: Array<UserMemberFormTeamRoleData>;
    team_permissions: Array<UserMemberFormTeamPermissionData>;
};
export type UserMemberFormTeamPermissionData = {
    team_id: number;
    permission: PermissionName;
    can_update?: boolean;
};
export type UserMemberFormTeamRoleData = {
    team_id: number;
    role: RoleName;
    can_update?: boolean;
};
export type UserMemberIndexProps = {
    request: UserMemberIndexRequest;
    users?: {
        data: Array<UserMemberIndexResource>;
        links: Array<{ url: string; label: string; active: boolean }>;
        meta: {
            current_page: number;
            first_page_url: string;
            from: number;
            last_page: number;
            last_page_url: string;
            next_page_url: string;
            path: string;
            per_page: number;
            prev_page_url: string;
            to: number;
            total: number;
        };
    };
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type UserMemberIndexRequest = {
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
};
export type UserMemberIndexResource = {
    id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    email: string;
    phone?: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
    avatar?: MediaResource;
};
export type UserMemberOneOrManyRequest = {
    user?: number;
    ids?: Array<number>;
};
export type UserResource = {
    id: number;
    owner_id: number;
    team_id?: number;
    first_name: string;
    last_name: string;
    full_name: string;
    email: string;
    phone?: string;
    is_admin: boolean;
    is_owner: boolean;
    is_member: boolean;
    avatar?: MediaResource;
    teams?: Array<TeamListResource>;
};
export type VerifyEmailProps = {};
export type VerifyEmailRequest = {
    code: string;
};
