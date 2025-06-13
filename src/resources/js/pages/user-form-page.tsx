import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Teszt',
        href: '/test',
    },
];

export default function UserFormPage() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            ssssss
        </AppLayout>
    );
}
