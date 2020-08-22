import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import 'rxjs/add/observable/of';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/share';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DashboardService {
  public countData: any;
  public countDataObs: any;

  constructor(private http: HttpClient) { }

  getDashboardData() {
    if (this.countData) {
      return Observable.of(this.countData);
    } else if (this.countDataObs) {
      return this.countDataObs;
    } else {
      this.countDataObs = this.http.get(`${environment.apiUrl}/count-all`, {
        observe: 'response'
      })
        .map(response => {
          this.countDataObs = null;
          if (response.status === 200) {
            this.countData = response.body;
            return this.countData;
          } else {
            return 'Request failed.';
          }
        })
        .share();
      return this.countDataObs;
    }
  }
}
