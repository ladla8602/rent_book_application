import { Component, EventEmitter, OnInit, Output } from '@angular/core';
import { NavigationEnd, Router, UrlTree, UrlSegmentGroup, PRIMARY_OUTLET } from '@angular/router';
import { Location } from '@angular/common';
import { AuthenticationService } from 'src/app/@shared/services/authentication.service';
import { User, Role } from 'src/app/@shared/models';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

    isActive: boolean;
    collapsed: boolean;
    showMenu: string;
    pushRightClass: string;
    currentUser: User;
    tree: UrlTree;
    primary: UrlSegmentGroup;
    @Output() collapsedEvent = new EventEmitter<boolean>();

    constructor(
      private location: Location,
      public router: Router,
      private authService: AuthenticationService) {
        this.authService.currentUser.subscribe(x => this.currentUser = x);
        this.router.events.subscribe((val) => {
            if (val instanceof NavigationEnd && window.innerWidth <= 992 && this.isToggled()) {
                this.toggleSidebar();
            }
        });
    }

    ngOnInit() {
        this.isActive = false;
        this.collapsed = false;
        this.showMenu = '';
        this.pushRightClass = 'push-right';
    }

    get isAdmin() {
      return this.currentUser && this.currentUser.role === Role.Admin;
    }

    get isRenter() {
      return this.currentUser && this.currentUser.role === Role.Renter;
    }

    isUSERPage() {
      this.tree = this.router.parseUrl(this.location.path());
      this.primary = this.tree.root.children[PRIMARY_OUTLET];
      if (this.primary.segments[0].path === 'user') {
        return true;
      }
      return false;
    }

    eventCalled() {
        this.isActive = !this.isActive;
    }

    addExpandClass(element: any) {
        if (element === this.showMenu) {
            this.showMenu = '0';
        } else {
            this.showMenu = element;
        }
    }

    toggleCollapsed() {
        this.collapsed = !this.collapsed;
        this.collapsedEvent.emit(this.collapsed);
    }

    isToggled(): boolean {
        const dom: Element = document.querySelector('body');
        return dom.classList.contains(this.pushRightClass);
    }

    toggleSidebar() {
        const dom: any = document.querySelector('body');
        dom.classList.toggle(this.pushRightClass);
    }

    onLoggedout() {
        localStorage.removeItem('isLoggedin');
    }

}
