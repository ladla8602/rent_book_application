import { DashboardService } from './../../services/dashboard.service';
import { Component, OnInit, OnDestroy } from '@angular/core';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit, OnDestroy {
  public counts: any = {};
  constructor(private dashboardService: DashboardService) { }

  ngOnInit(): void {
    this.loadCounterData();
  }

  ngOnDestroy() { }

  loadCounterData() {
    this.dashboardService.getDashboardData().subscribe(
      (response: any) => {
        const data = response.result;
        this.counts = data;
      },
      (error) => {
        console.log(error);
      }
      );
  }
}
