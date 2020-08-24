import { BookRentHistoryComponent } from './components/book-rent-history/book-rent-history.component';
import { BookEditComponent } from './components/book-edit/book-edit.component';
import { BookAddComponent } from './components/book-add/book-add.component';
import { BookListComponent } from './components/book-list/book-list.component';
import { AuthGuard } from './@core/guards/auth.guard';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './components/auth/login/login.component';
import { RegisterComponent } from './components/auth/register/register.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { Role } from './@shared/models/role';

const routes: Routes = [
  {
    path: 'auth',
    children: [

      {
        path: 'login',
        component: LoginComponent
      },
      {
        path: 'register',
        component: RegisterComponent
      }
    ]
  },
  {
    path: 'dashboard',
    canActivate: [AuthGuard],
    data: { roles: [Role.Renter, Role.Admin] },
    component: DashboardComponent
  },
  {
    path: 'book-list',
    canActivate: [AuthGuard],
    component: BookListComponent
  },
  {
    path: 'add-new-book',
    canActivate: [AuthGuard],
    data: { roles: [Role.Admin] },
    component: BookAddComponent
  },
  {
    path: 'book-list/edit/:bookId',
    canActivate: [AuthGuard],
    data: { roles: [Role.Admin] },
    component: BookEditComponent
  },
  {
    path: 'rent-history',
    canActivate: [AuthGuard],
    data: { roles: [Role.Renter] },
    component: BookRentHistoryComponent
  },
  {
    path: '', redirectTo: '/dashboard', pathMatch: 'full'
  },
  {
    path: '**', redirectTo: '/dashboard', pathMatch: 'full'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
