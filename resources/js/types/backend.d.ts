export type AccountingPeriodFormProps = {
    team: TeamListResource;
    accountingPeriod?: AccountingPeriodFormResource;
};
export type AccountingPeriodFormRequest = {
    starts_at: string;
    ends_at: string;
};
export type AccountingPeriodFormResource = {
    id: number;
    label: string;
    starts_at: string;
    ends_at: string;
};
export type AccountingPeriodIndexProps = {
    request: AccountingPeriodIndexRequest;
    team: TeamListResource;
    accountingPeriods?: {
        data: Array<AccountingPeriodIndexResource>;
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
export type AccountingPeriodIndexResource = {
    id: number;
    label: string;
    starts_at: string;
    ends_at: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
};
export type AccountingPeriodOneOrManyRequest = {
    accounting_period?: number;
    ids?: Array<number>;
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
export type ExpenseCategoryFormProps = {
    team: TeamListResource;
    expenseType: ExpenseType;
    expenseCategory?: ExpenseCategoryFormResource;
};
export type ExpenseCategoryFormRequest = {
    name: string;
};
export type ExpenseCategoryFormResource = {
    id: number;
    type: ExpenseType;
    name: string;
};
export type ExpenseCategoryIndexProps = {
    request: ExpenseCategoryIndexRequest;
    team: TeamListResource;
    expenseTypes?: Array<{ value: ExpenseType; label: string }>;
    expenseType: ExpenseType;
    expenseCategories?: {
        data: Array<ExpenseCategoryIndexResource>;
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
export type ExpenseCategoryIndexResource = {
    id: number;
    type: ExpenseType;
    name: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
};
export type ExpenseCategoryListResource = {
    id: number;
    type: ExpenseType;
    name: string;
};
export type ExpenseCategoryOneOrManyRequest = {
    expense_category?: number;
    ids?: Array<number>;
};
export type ExpenseItemFormProps = {
    team: TeamListResource;
    expenseType: ExpenseType;
    expenseCategories?: Array<ExpenseCategoryListResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryListResource>;
    expenseItem?: ExpenseItemFormResource;
};
export type ExpenseItemFormRequest = {
    expense_category_id: number;
    expense_sub_category_id?: number;
    name: string;
};
export type ExpenseItemFormResource = {
    id: number;
    type: ExpenseType;
    expense_category: ExpenseCategoryListResource;
    expense_sub_category?: ExpenseSubCategoryListResource;
    name: string;
};
export type ExpenseItemIndexProps = {
    request: ExpenseItemIndexRequest;
    team: TeamListResource;
    expenseTypes?: Array<{ value: ExpenseType; label: string }>;
    expenseType: ExpenseType;
    expenseItems?: {
        data: Array<ExpenseItemIndexResource>;
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
    expenseCategories?: Array<ExpenseCategoryListResource>;
    expenseSubCategories?: Array<ExpenseSubCategoryListResource>;
    trashedFilters?: Array<{ value: TrashedFilter; label: string }>;
};
export type ExpenseItemIndexRequest = {
    expense_categories?: Array<ExpenseCategoryListResource>;
    expense_sub_categories?: Array<ExpenseSubCategoryListResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    expense_category_ids?: Array<number>;
    expense_sub_category_ids?: Array<number>;
};
export type ExpenseItemIndexResource = {
    id: number;
    type: ExpenseType;
    expense_category: ExpenseCategoryListResource;
    expense_sub_category?: ExpenseSubCategoryListResource;
    name: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
};
export type ExpenseItemListResource = {
    id: number;
    name: string;
};
export type ExpenseItemOneOrManyRequest = {
    expense_item?: number;
    ids?: Array<number>;
};
export type ExpenseSubCategoryFormProps = {
    team: TeamListResource;
    expenseType: ExpenseType;
    expenseCategories?: Array<ExpenseCategoryListResource>;
    expenseSubCategory?: ExpenseSubCategoryFormResource;
};
export type ExpenseSubCategoryFormRequest = {
    expense_category_id: number;
    name: string;
};
export type ExpenseSubCategoryFormResource = {
    id: number;
    expense_category: ExpenseCategoryListResource;
    name: string;
};
export type ExpenseSubCategoryIndexProps = {
    request: ExpenseSubCategoryIndexRequest;
    team: TeamListResource;
    expenseTypes?: Array<{ value: ExpenseType; label: string }>;
    expenseType: ExpenseType;
    expenseCategories?: Array<ExpenseCategoryListResource>;
    expenseSubCategories?: {
        data: Array<ExpenseSubCategoryIndexResource>;
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
export type ExpenseSubCategoryIndexRequest = {
    expense_categories?: Array<ExpenseCategoryListResource>;
    q?: string;
    page?: number;
    per_page?: number;
    sort_by: string;
    sort_direction: string;
    trashed?: TrashedFilter;
    expense_category_ids?: Array<number>;
};
export type ExpenseSubCategoryIndexResource = {
    id: number;
    expense_category: ExpenseCategoryListResource;
    name: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
};
export type ExpenseSubCategoryListResource = {
    id: number;
    expense_category?: ExpenseCategoryListResource;
    name: string;
};
export type ExpenseSubCategoryOneOrManyRequest = {
    expense_sub_category?: number;
    ids?: Array<number>;
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
export type PermissionName = 'client';
export type ProjectDepartmentFormProps = {
    team: TeamListResource;
    projectDepartment?: ProjectDepartmentFormResource;
};
export type ProjectDepartmentFormRequest = {
    name: string;
};
export type ProjectDepartmentFormResource = {
    id: number;
    name: string;
};
export type ProjectDepartmentIndexProps = {
    request: ProjectDepartmentIndexRequest;
    team: TeamListResource;
    projectDepartments?: {
        data: Array<ProjectDepartmentIndexResource>;
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
export type ProjectDepartmentIndexResource = {
    id: number;
    name: string;
    can_view: boolean;
    can_update: boolean;
    can_trash: boolean;
    can_restore: boolean;
    can_delete: boolean;
    deleted_at?: string;
};
export type ProjectDepartmentOneOrManyRequest = {
    project_department?: number;
    ids?: Array<number>;
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
};
export type TeamFormResource = {
    id: number;
    creator_id: number;
    name: string;
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
    creator_id: number;
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
};
export type TeamOneOrManyRequest = {
    team?: number;
    ids?: Array<number>;
};
export type ToastMessagesData = {
    info?: string;
    success?: string;
    warning?: string;
    error?: string;
};
export type TrashedFilter = 'only' | 'with';
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
    avatar?: MediaResource | null;
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
