import { Role } from './role';

export class User {
    id: number;
    name: string;
    username: string;
    email: string;
    role: Role;
    token?: string;
}
