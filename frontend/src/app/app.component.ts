import { Router } from '@angular/router';
import { AuthenticationService } from './@shared/services/authentication.service';
import { Component } from '@angular/core';
import { Role, User } from './@shared/models';
import * as $ from 'jquery';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  collapedSideBar: boolean;
  user: User;

  constructor(private authenticationService: AuthenticationService, private router: Router) {
    this.authenticationService.currentUser.subscribe(x => this.user = x);
    if (this.authenticationService.isLoggedIn) {
      this.router.navigate(['/dashboard']);
    }
  }

  receiveCollapsed($event) {
    this.collapedSideBar = $event;
  }

  get isAdmin() {
    return this.user && this.user.role === Role.Admin;
  }

  get isRenter() {
    return this.user && this.user.role === Role.Renter;
  }

  logout() {
    this.authenticationService.logout();
  }
}
