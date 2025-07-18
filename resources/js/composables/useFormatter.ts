import { DateValue, getLocalTimeZone } from '@internationalized/date';
import { useDateFormatter } from 'reka-ui';
import { useLocale } from './useLocale';
import { useParser } from './useParser';

export function useFormatter() {
    const parse = useParser();

    const { locale } = useLocale();

    return {
        date(value?: DateValue | string, options?: Intl.DateTimeFormatOptions): string {
            try {
                const date = typeof value === 'string' ? parse.toDate(value) : value;
                if (!date) {
                    return '';
                }
                const formatter = useDateFormatter(locale.value);
                return formatter.custom(date.toDate(getLocalTimeZone()), options ?? { dateStyle: 'medium' });
            } catch (error) {
                console.error(error);
                return '';
            }
        },
        timestamp(value?: DateValue | string): string {
            try {
                const date = parse.toDateTime(value);
                return date?.toString() ?? '';
            } catch (error) {
                console.error(error);
                return '';
            }
        },
        unix(value?: DateValue | string): number {
            try {
                const date = parse.toDateTime(value);
                return date?.toDate(getLocalTimeZone()).getTime() ?? 0;
            } catch (error) {
                console.error(error);
                return 0;
            }
        },
        price(value?: number | string): string {
            if (value == undefined) {
                return '';
            }

            const number = Number(value);
            if (isNaN(Number(value))) {
                return '';
            }

            return Intl.NumberFormat(locale.value, {
                style: 'currency',
                currency: 'EUR',
            }).format(number);
        },
    };
}
