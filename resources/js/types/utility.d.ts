export type PartialRequired<T, K extends keyof T> = Omit<T, K> & Required<Pick<T, K>>;
export type PartialPick<T, K extends keyof T> = Partial<T> & Pick<T, K>;
export type PartialNullable<T, K extends keyof T> = Omit<T, K> & Partial<Pick<T, K>>;
