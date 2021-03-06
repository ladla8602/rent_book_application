import { ErrorInterceptor, JwtInterceptor } from './@core/interceptors';
import { BrowserModule } from '@angular/platform-browser';
import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { MaterialModule } from './material.module';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './components/auth/login/login.component';
import { RegisterComponent } from './components/auth/register/register.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule, HttpClient, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthenticationService } from './@shared/services/authentication.service';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { SidebarComponent } from './components/sidebar/sidebar.component';
import { BookListComponent } from './components/book-list/book-list.component';
import { BookAddComponent } from './components/book-add/book-add.component';
import { BookEditComponent } from './components/book-edit/book-edit.component';
import { BookRentHistoryComponent } from './components/book-rent-history/book-rent-history.component';
import { AlertDialogBoxComponent } from './components/alert-dialog-box/alert-dialog-box.component';
import { BookAddService } from './services/book-add.service';
import { BookListService } from './services/book-list.service';
import { CdkColumnDef } from '@angular/cdk/table';
import { BookDeleteDialogBoxComponent } from './components/book-list/book-delete-dialog-box/book-delete-dialog-box.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    DashboardComponent,
    SidebarComponent,
    BookListComponent,
    BookAddComponent,
    BookEditComponent,
    BookRentHistoryComponent,
    AlertDialogBoxComponent,
    BookDeleteDialogBoxComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    NgbModule,
    FormsModule,
    ReactiveFormsModule,
    MaterialModule,
    AppRoutingModule,
    HttpClientModule,
  ],
  providers: [AuthenticationService, BookAddService, BookListService, CdkColumnDef,
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },
    { provide: HTTP_INTERCEPTORS, useClass: ErrorInterceptor, multi: true }
  ],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
  entryComponents: [AlertDialogBoxComponent, BookDeleteDialogBoxComponent]
})
export class AppModule { }
