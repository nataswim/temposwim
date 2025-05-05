import React from 'react';
import { Outlet } from 'react-router-dom';
import UserHeader from '../components/user/UserHeader';
import UserFooter from '../components/user/UserFooter';
import RandomBanner from '../components/template/RandomBanner';

const UserLayout = () => {
  return (
    <div className="user-layout">
      <UserHeader />
      <div className="user-content">
      <RandomBanner />

        <Outlet />
      </div>
      <UserFooter />
    </div>
  );
};

export default UserLayout;