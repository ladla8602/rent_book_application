import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ROLE } from '../../../@core/enums/role';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  public registerForm: FormGroup;
  public loading = false;
  public submitted = false;
  public returnUrl: string;
  public error = '';
  constructor(
    private route: ActivatedRoute,
    public router: Router,
    private formBuilder: FormBuilder
  ) { }

  ngOnInit(): void {
    this.registerForm = this.formBuilder.group({
      name: ['', Validators.required],
      username: ['', Validators.required],
      email: ['', Validators.compose([Validators.required,Validators.email])],
      password: ['', Validators.required],
      role: ROLE.RENTER
    });
    this.returnUrl = this.route.snapshot.queryParams.returnUrl || '/dashboard';
  }

  onSubmit() {
    this.submitted = true;
    this.loading = true;
    // stop here if form is invalid
    if (this.registerForm.invalid) {
      return;
    }

    this.loading = true;
    // this.authService.login(this.f.email.value, this.f.password.value)
    //   .pipe(first())
    //   .subscribe(
    //     data => {
    //       if (data.role === 'Admin') {
    //         this.router.navigate([this.returnUrl]);
    //       }
    //       if (data.role === 'User') {
    //         this.router.navigate(['/user/dashboard']);
    //       }
    //     },
    //     error => {
    //       this.error = error;
    //       this.loading = false;
    //     });
  }

}
