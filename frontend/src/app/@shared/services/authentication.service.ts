import { Router } from '@angular/router';
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { environment } from '../../../environments/environment';
import { User } from '../models/user';
@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {
  private currentUserSubject: BehaviorSubject<User>;
  public currentUser: Observable<User>;
  private loggedIn: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);

  get isLoggedIn() {
    return this.loggedIn.asObservable();
  }

  constructor(private http: HttpClient, private router: Router) {
    this.currentUserSubject = new BehaviorSubject<User>(JSON.parse(localStorage.getItem('currentUser')));
    this.currentUser = this.currentUserSubject.asObservable();
  }

  public get currentUserValue(): User {
    return this.currentUserSubject.value;
  }

  login(email: string, password: string) {
    return this.http.post<any>(`${environment.apiUrl}/login`, { email, password })
      .pipe(map(response => {
        // login successful if there's a jwt token in the response
        if (response.result[0].user && response.result[0].token) {
          // store user details and jwt token in local storage to keep user logged in between page refreshes
          response.result[0].user.token = response.result[0].token;
          localStorage.setItem('currentUser', JSON.stringify(response.result[0].user));
          this.loggedIn.next(true);
          this.currentUserSubject.next(response.result[0].user);
        }

        return response.result[0].user;
      }));
  }

  register(body: any) {
    return this.http.post<any>(`${environment.apiUrl}/register`, body);
  }

  logout() {
    // remove user from local storage to log user out
    localStorage.removeItem('currentUser');
    this.currentUserSubject.next(null);
    this.router.navigate(['/auth/login']);
  }
}
