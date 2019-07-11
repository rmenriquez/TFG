import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { routing, appRoutingProviders} from './app.routing';

//Componentes
import { AppComponent } from './app.component';

import { BasicAuthInterceptor } from './_helpers/basic-auth.interceptor';
//User
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
//Default
import { DefaultComponent } from './components/default/default.component';
//Food
import { FoodNewComponent } from './components/food-new/food-new.component';
import { FoodEditComponent } from './components/food-edit/food-edit.component';
import { FoodDetailComponent } from './components/food-detail/food-detail.component';
import { FoodAllComponent } from './components/food-all/food-all.component';
import { FoodSetAllergen } from './components/food-set-allergen/food-set-allergen.component';
//Event
import { EventNewComponent } from './components/event-new/event-new.component';
import { EventAllComponent } from './components/event-all/event-all.component';
import { EventEditComponent } from './components/event-edit/event-edit.component';
import { EventDetailComponent } from './components/event-detail/event-detail.component';
//Staff
import { StaffNewComponent } from './components/staff-new/staff-new.component';
import { StaffAllComponent } from './components/staff-all/staff-all.component';
import { StaffDetailComponent } from './components/staff-detail/staff-detail.component';
import { StaffEditComponent } from './components/staff-edit/staff-edit.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    DefaultComponent,
    FoodNewComponent,
    FoodEditComponent,
    FoodDetailComponent,
    FoodAllComponent,
    FoodSetAllergen,
    EventNewComponent,
    EventAllComponent,
    EventDetailComponent,
    EventEditComponent,
    StaffNewComponent,
    StaffAllComponent,
    StaffDetailComponent,
    StaffEditComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    routing
  ],
  providers: [
      appRoutingProviders,
    { provide: HTTP_INTERCEPTORS, useClass: BasicAuthInterceptor, multi: true },

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
