import { AuthenticationService } from 'src/app/@shared/services/authentication.service';
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
    private formBuilder: FormBuilder,
    private authService: AuthenticationService
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

    this.loading = true;
    // stop here if form is invalid
    if (this.registerForm.invalid) {
      return;
    }

    const formData = new FormData();
    formData.append('name', this.registerForm.get('name').value);
    formData.append('username', this.registerForm.get('username').value);
    formData.append('email', this.registerForm.get('email').value);
    formData.append('password', this.registerForm.get('password').value);
    formData.append('role', this.registerForm.get('role').value);

    this.authService.register(formData).subscribe(
      (response) => {
        this.submitted = true;
        this.loading = false;
        console.log(response);
      },
      (error) => {
        console.log(error);
      }
    );
  }

}
