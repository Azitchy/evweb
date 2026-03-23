class User {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String role;
  final bool isActive;
  final Wallet? wallet;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.role,
    required this.isActive,
    this.wallet,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      role: json['role'],
      isActive: json['is_active'] ?? true,
      wallet: json['wallet'] != null ? Wallet.fromJson(json['wallet']) : null,
    );
  }
}

class Wallet {
  final int id;
  final int userId;
  final double balance;

  Wallet({required this.id, required this.userId, required this.balance});

  factory Wallet.fromJson(Map<String, dynamic> json) {
    return Wallet(
      id: json['id'],
      userId: int.parse(json['user_id'].toString()),
      balance: double.parse(json['balance'].toString()),
    );
  }
}

class ChargingSession {
  final int id;
  final int userId;
  final double startPercentage;
  final double? endPercentage;
  final double? chargedPercentage;
  final double? cost;
  final double pricePerPercentage;
  final String status;
  final DateTime startedAt;
  final DateTime? endedAt;

  ChargingSession({
    required this.id,
    required this.userId,
    required this.startPercentage,
    this.endPercentage,
    this.chargedPercentage,
    this.cost,
    required this.pricePerPercentage,
    required this.status,
    required this.startedAt,
    this.endedAt,
  });

  factory ChargingSession.fromJson(Map<String, dynamic> json) {
    return ChargingSession(
      id: json['id'],
      userId: int.parse(json['user_id'].toString()),
      startPercentage: double.parse(json['start_percentage'].toString()),
      endPercentage: json['end_percentage'] != null
          ? double.parse(json['end_percentage'].toString())
          : null,
      chargedPercentage: json['charged_percentage'] != null
          ? double.parse(json['charged_percentage'].toString())
          : null,
      cost: json['cost'] != null ? double.parse(json['cost'].toString()) : null,
      pricePerPercentage: double.parse(json['price_per_percentage'].toString()),
      status: json['status'],
      startedAt: DateTime.parse(json['started_at']),
      endedAt: json['ended_at'] != null
          ? DateTime.parse(json['ended_at'])
          : null,
    );
  }
}

class Transaction {
  final int id;
  final int userId;
  final int walletId;
  final int? chargingSessionId;
  final String type;
  final double amount;
  final double balanceAfter;
  final String description;
  final DateTime createdAt;

  Transaction({
    required this.id,
    required this.userId,
    required this.walletId,
    this.chargingSessionId,
    required this.type,
    required this.amount,
    required this.balanceAfter,
    required this.description,
    required this.createdAt,
  });

  factory Transaction.fromJson(Map<String, dynamic> json) {
    return Transaction(
      id: json['id'],
      userId: int.parse(json['user_id'].toString()),
      walletId: int.parse(json['wallet_id'].toString()),
      chargingSessionId: json['charging_session_id'] != null
          ? int.parse(json['charging_session_id'].toString())
          : null,
      type: json['type'],
      amount: double.parse(json['amount'].toString()),
      balanceAfter: double.parse(json['balance_after'].toString()),
      description: json['description'],
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}

class ChargingStation {
  final int id;
  final String name;
  final String address;
  final double latitude;
  final double longitude;
  final String status;
  final int totalPorts;
  final int availablePorts;
  final String? chargerType;
  final double? powerKw;
  final String? description;
  final String? imageUrl;
  final double? distance;

  ChargingStation({
    required this.id,
    required this.name,
    required this.address,
    required this.latitude,
    required this.longitude,
    required this.status,
    required this.totalPorts,
    required this.availablePorts,
    this.chargerType,
    this.powerKw,
    this.description,
    this.imageUrl,
    this.distance,
  });

  factory ChargingStation.fromJson(Map<String, dynamic> json) {
    return ChargingStation(
      id: json['id'],
      name: json['name'],
      address: json['address'],
      latitude: double.parse(json['latitude'].toString()),
      longitude: double.parse(json['longitude'].toString()),
      status: json['status'],
      totalPorts: int.parse(json['total_ports'].toString()),
      availablePorts: int.parse(json['available_ports'].toString()),
      chargerType: json['charger_type'],
      powerKw: json['power_kw'] != null
          ? double.parse(json['power_kw'].toString())
          : null,
      description: json['description'],
      imageUrl: json['image_url'],
      distance: json['distance'] != null
          ? double.parse(json['distance'].toString())
          : null,
    );
  }
}

class SubscriptionPlan {
  final int id;
  final String name;
  final String? description;
  final double price;
  final int durationDays;
  final double discountPercentage;
  final double freeChargingPercentage;
  final bool prioritySupport;

  SubscriptionPlan({
    required this.id,
    required this.name,
    this.description,
    required this.price,
    required this.durationDays,
    required this.discountPercentage,
    required this.freeChargingPercentage,
    required this.prioritySupport,
  });

  factory SubscriptionPlan.fromJson(Map<String, dynamic> json) {
    return SubscriptionPlan(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      price: double.parse(json['price'].toString()),
      durationDays: int.parse(json['duration_days'].toString()),
      discountPercentage: double.parse(json['discount_percentage'].toString()),
      freeChargingPercentage: double.parse(
        json['free_charging_percentage'].toString(),
      ),
      prioritySupport: json['priority_support'] ?? false,
    );
  }
}

class UserSubscription {
  final int id;
  final int userId;
  final int subscriptionPlanId;
  final DateTime startsAt;
  final DateTime expiresAt;
  final String status;
  final double amountPaid;
  final SubscriptionPlan? plan;

  UserSubscription({
    required this.id,
    required this.userId,
    required this.subscriptionPlanId,
    required this.startsAt,
    required this.expiresAt,
    required this.status,
    required this.amountPaid,
    this.plan,
  });

  bool get isActive => status == 'active' && expiresAt.isAfter(DateTime.now());

  factory UserSubscription.fromJson(Map<String, dynamic> json) {
    return UserSubscription(
      id: json['id'],
      userId: int.parse(json['user_id'].toString()),
      subscriptionPlanId: int.parse(json['subscription_plan_id'].toString()),
      startsAt: DateTime.parse(json['starts_at']),
      expiresAt: DateTime.parse(json['expires_at']),
      status: json['status'],
      amountPaid: double.parse(json['amount_paid'].toString()),
      plan: json['subscription_plan'] != null
          ? SubscriptionPlan.fromJson(json['subscription_plan'])
          : null,
    );
  }
}

class AppNotification {
  final int id;
  final String title;
  final String body;
  final String type;
  final bool isRead;
  final DateTime createdAt;

  AppNotification({
    required this.id,
    required this.title,
    required this.body,
    required this.type,
    required this.isRead,
    required this.createdAt,
  });

  factory AppNotification.fromJson(Map<String, dynamic> json) {
    return AppNotification(
      id: json['id'],
      title: json['title'],
      body: json['body'],
      type: json['type'] ?? 'general',
      isRead: json['is_read'] ?? false,
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
