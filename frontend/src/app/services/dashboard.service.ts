import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DashboardService {
  public countData: any;
  public countDataObs: any;

  constructor(private http: HttpClient) { }

  getDashboardData() {
    return this.http.get(`${environment.apiUrl}/count-all`);
  }
}
