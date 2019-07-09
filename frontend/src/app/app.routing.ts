/**
 * Created by RaquelMarcos on 17/6/19.
 */
import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//Componentes
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { DefaultComponent } from './components/default/default.component';
import { FoodNewComponent } from './components/food-new/food-new.component';
import { FoodAllComponent } from './components/food-all/food-all.component';
import { FoodDetailComponent } from './components/food-detail/food-detail.component';
import { FoodEditComponent } from './components/food-edit/food-edit.component';
import { EventNewComponent } from './components/event-new/event-new.component';
import { EventAllComponent } from './components/event-all/event-all.component';
import { EventEditComponent } from './components/event-edit/event-edit.component';
import { EventDetailComponent } from './components/event-detail/event-detail.component';
import { StaffNewComponent } from './components/staff-new/staff-new.component';
import { StaffAllComponent } from './components/staff-all/staff-all.component';
import { StaffDetailComponent } from './components/staff-detail/staff-detail.component';
import { StaffEditComponent } from './components/staff-edit/staff-edit.component';


const appRoutes: Routes = [
    {path: '', component: DefaultComponent},
    {path: 'home', component: DefaultComponent},
    {path: 'login', component: LoginComponent},
    {path: 'logout/:sure', component: LoginComponent},
    {path: 'register', component: RegisterComponent},
    {path: 'newFood', component: FoodNewComponent},
    {path: 'allFoods', component: FoodAllComponent},
    {path: 'editFood/:id', component: FoodEditComponent},
    {path: 'foodDetail/:id', component: FoodDetailComponent},
    {path: 'newEvent', component: EventNewComponent},
    {path: 'allEvents', component: EventAllComponent},
    {path: 'editEvent/:id', component: EventEditComponent},
    {path: 'eventDetail/:id', component: EventDetailComponent},
    {path: 'newStaff', component: StaffNewComponent},
    {path: 'allStaff', component: StaffAllComponent},
    {path: 'staffDetail/:id', component: StaffDetailComponent},
    {path: 'editStaff/:id', component: StaffEditComponent},
    {path: '**', component: DefaultComponent}
];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
